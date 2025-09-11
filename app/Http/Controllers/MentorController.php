<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Mail\MentorShareMail;

class MentorController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();

        // Users who shared with this mentor
        $clients = User::whereIn('id', function ($q) use ($mentor) {
            $q->from('mentor_user_shares')->select('user_id')->where('mentor_id', $mentor->id);
        })->orderBy('name')->get();

        return view('mentor.index', compact('clients'));
    }

    public function clients(Request $request)
    {
        // List mentors for current user to share with
        $mentors = Role::where('name', 'mentor')->first()?->users()->where('status', 1)->orderBy('name')->get() ?? collect();
        return response()->json(['mentors' => $mentors->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])]);
    }

    public function share(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $mentorId = (int) $request->mentor_id;

        // Ensure target user has mentor role
        $mentor = User::findOrFail($mentorId);
        if (! $mentor->hasRole('mentor')) {
            return back()->with('error', 'Selected user is not a mentor.');
        }

        DB::table('mentor_user_shares')->updateOrInsert(
            ['user_id' => $user->id, 'mentor_id' => $mentorId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Send email to mentor (immediate send to avoid stale queued payloads)
        Mail::to($mentor->email)->send(new MentorShareMail($user));

        return back()->with('success', 'Shared with mentor successfully.');
    }

    public function clientReports(User $client)
    {
        $mentor = Auth::user();

        // Authorize mentor has access via share pivot
        $hasShare = DB::table('mentor_user_shares')
            ->where('mentor_id', $mentor->id)
            ->where('user_id', $client->id)
            ->exists();

        abort_unless($hasShare, 403);

        // We'll compute the same lists the client sees (self context)
        $surveyAverages = [];
        $distinctSurveys = collect();

        // Helpers and computed structures used by the original reports view
        // Note: These helpers reference auth()->user(), so temporarily impersonate the client
        $originalUser = Auth::user();
        try {
            Auth::setUser($client);
            // Replicate ReportsController@index self-logic for non-admin users
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $userSurveyRates = \App\Models\UsersSurveysRate::with('survey')
                    ->join('question_options', 'users_surveys_rates.options_id', '=', 'question_options.id')
                    ->select('users_surveys_rates.*', 'question_options.point')
                    ->get();
            } else {
                $userSurveyRates = $user->usersSurveysRates()
                    ->with('survey')
                    ->join('question_options', 'users_surveys_rates.options_id', '=', 'question_options.id')
                    ->select('users_surveys_rates.*', 'question_options.point')
                    ->get();
            }

            foreach ($userSurveyRates->groupBy('survey_id') as $surveyId => $rates) {
                $surveyAverages[$surveyId] = $rates->avg('point');
            }
            $distinctSurveys = $userSurveyRates->pluck('survey')->filter()->unique('id')->values();
            $allGroupSurveyResults = function_exists('getAllGroupsCombinedTypeReportsCombinedByGroupType')
                ? getAllGroupsCombinedTypeReportsCombinedByGroupType()
                : [];
            $allreport = function_exists('allreport') ? allreport() : [];
            $surveytypequestion = function_exists('getAllSelfAwarenessQuestionsFlatByGroupType')
                ? getAllSelfAwarenessQuestionsFlatByGroupType()
                : ['individual' => ['questions' => []], 'family' => ['questions' => []], 'friend' => ['questions' => []]];
        } finally {
            Auth::setUser($originalUser);
        }

        // Labels and datasets logic (mirrors ReportsController@index)
        $groups = ['individual', 'family', 'friend'];
        $maxGroup = null;
        $maxCount = 0;
        foreach ($groups as $group) {
            $count = count($surveytypequestion[$group]['questions'] ?? []);
            if ($count > $maxCount) {
                $maxCount = $count;
                $maxGroup = $group;
            }
        }
        $labels = [];
        if ($maxGroup) {
            $labels = collect($surveytypequestion[$maxGroup]['questions'])->pluck('question_text')->toArray();
        }
        if (empty($labels)) {
            $labels = ['No Questions Available'];
        }

        $datasets = [];
        $datasets[] = [
            'label' => 'Overall',
            'data' => collect($surveytypequestion[$maxGroup]['questions'] ?? [])->map(function ($q, $index) use ($surveytypequestion, $groups) {
                $getValue = function ($type, $index, $field) use ($surveytypequestion) {
                    return isset($surveytypequestion[$type]['questions'][$index][$field])
                        ? $surveytypequestion[$type]['questions'][$index][$field]
                        : 0;
                };
                $totalRatings = 0;
                $totalPoints = 0;
                foreach ($groups as $group) {
                    $totalRatings += $getValue($group, $index, 'self_total_ratings');
                    $totalRatings += $getValue($group, $index, 'others_total_ratings');
                    $totalPoints += $getValue($group, $index, 'self_total_points');
                    $totalPoints += $getValue($group, $index, 'others_total_points');
                }
                return $totalRatings > 0
                    ? round(($totalPoints / ($totalRatings * 5)) * 100, 0)
                    : 0;
            })->toArray(),
            'backgroundColor' => '#9b59b6'
        ];

        $datasets[] = [
            'label' => 'Self-Evaluation',
            'data' => collect($surveytypequestion['individual']['questions'] ?? [])->map(function ($q) {
                return ($q['self_total_ratings'] ?? 0) > 0
                    ? round((($q['self_total_points'] ?? 0) / (($q['self_total_ratings'] ?? 0) * 5)) * 100, 0)
                    : 0;
            })->toArray(),
            'backgroundColor' => '#1abc9c'
        ];

        $datasets[] = [
            'label' => 'Family Members',
            'data' => collect($surveytypequestion['family']['questions'] ?? [])->map(function ($q) {
                $totalPoints = ($q['others_total_points'] ?? 0) + ($q['self_total_points'] ?? 0);
                $totalRatings = ($q['others_total_ratings'] ?? 0) + ($q['self_total_ratings'] ?? 0);
                return $totalRatings > 0
                    ? round(($totalPoints / ($totalRatings * 5)) * 100, 2)
                    : 0;
            })->toArray(),
            'backgroundColor' => '#f39c12'
        ];

        $datasets[] = [
            'label' => 'Friends',
            'data' => collect($surveytypequestion['friend']['questions'] ?? [])->map(function ($q) {
                $totalPoints = ($q['others_total_points'] ?? 0) + ($q['self_total_points'] ?? 0);
                $totalRatings = ($q['others_total_ratings'] ?? 0) + ($q['self_total_ratings'] ?? 0);
                return $totalRatings > 0
                    ? round(($totalPoints / ($totalRatings * 5)) * 100, 2)
                    : 0;
            })->toArray(),
            'backgroundColor' => '#e74c3c'
        ];

        // Render the same reports view with all required data
        return view('reports.reports-index', [
            'UserSurveys' => $distinctSurveys,
            'surveyAverages' => $surveyAverages,
            'allGroupSurveyResults' => $allGroupSurveyResults,
            'allreport' => $allreport,
            'surveytypequestion' => $surveytypequestion,
            'labels' => $labels,
            'datasets' => $datasets,
            'isMentorView' => true,
        ]);
    }
}


