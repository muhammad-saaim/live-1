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
use App\Models\Service;
use App\Models\Invoice;

class MentorController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();

        // Users who shared with this mentor
        $clients = User::whereIn('users.id', function ($q) use ($mentor) {
            $q->from('mentor_user_shares')
              ->select('mentor_user_shares.user_id')
              ->where('mentor_user_shares.mentor_id', $mentor->id);
        })
        ->join('mentor_user_shares', 'users.id', '=', 'mentor_user_shares.user_id')
        ->where('mentor_user_shares.mentor_id', $mentor->id)
        ->select('users.*', 'mentor_user_shares.created_at as shared_date')
        ->orderBy('name')
        ->get();

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

        // Check if this share already exists to keep invoice creation idempotent
        $alreadyShared = DB::table('mentor_user_shares')
            ->where('user_id', $user->id)
            ->where('mentor_id', $mentorId)
            ->exists();

        // Upsert share record
        DB::table('mentor_user_shares')->updateOrInsert(
            ['user_id' => $user->id, 'mentor_id' => $mentorId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $message = 'Shared with mentor successfully.';
        $invoice = null;

        // If first-time share, create an invoice for mentoring service
        if (! $alreadyShared) {
            // Pick the configured mentoring service (prefer a single session)
            $service = Service::active()->where('name', 'Mentoring Session')->first()
                ?: Service::active()->byCategory('mentoring')->orderBy('price')->first();

            if ($service) {
                // Before creating a new invoice, check if ANY pending invoice exists for this user (most strict)
                $hasAnyPendingInvoice = Invoice::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->exists();

                if ($hasAnyPendingInvoice) {
                    // Requirement: if a pending mentoring invoice already exists, do NOT redirect
                    // Just inform the user with a message and keep $invoice null to avoid checkout redirect
                    $invoice = null;
                    $message = 'Invoice is not generated due to already have pending payment of mentoring.';
                } else {
                    DB::beginTransaction();
                    try {
                    $invoice = new Invoice();
                    $invoice->user_id = $user->id;
                    $invoice->invoice_number = $invoice->generateInvoiceNumber();
                    $invoice->issued_at = now();
                    $invoice->status = 'pending';
                    $invoice->billing_details = [
                        'context' => 'mentor_share',
                        'mentor_id' => $mentor->id,
                        'mentor_name' => $mentor->name,
                        'user_name' => $user->name,
                        'note' => 'Invoice generated on report share to mentor',
                    ];
                    $invoice->total_amount = $service->price; // quantity 1
                    $invoice->save();

                    $invoice->items()->create([
                        'service_id' => $service->id,
                        'service_name' => $service->name,
                        'unit_price' => $service->price,
                        'quantity' => 1,
                        'total_price' => $service->price,
                        'item_details' => [
                            'context' => 'mentor_share',
                            'mentor_id' => $mentor->id,
                            'mentor_name' => $mentor->name,
                        ],
                    ]);

                    DB::commit();

                    // Guide user to view/pay the invoice
                    $message = 'Shared with mentor successfully. An invoice has been generated for mentoring.';
                    } catch (\Throwable $e) {
                    DB::rollBack();
                    \Log::error('Failed creating mentor-share invoice: '.$e->getMessage(), [
                        'user_id' => $user->id,
                        'mentor_id' => $mentor->id,
                    ]);
                    // Keep share success even if billing failed
                    $message = 'Shared with mentor successfully, but invoice generation failed. Please visit Billing to create one.';
                    }
                }
            } else {
                // No mentoring services configured
                $message = 'Shared with mentor successfully. No active mentoring service configured to invoice.';
            }
        } else {
            // If already shared, block if ANY pending invoice exists for this user (most strict)
            $hasAnyPendingInvoice = Invoice::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();
            // If a pending invoice exists, do NOT redirect to checkout; just inform user and skip creating a new invoice
            if ($hasAnyPendingInvoice) {
                $invoice = null; // ensure no redirect happens
                $message = 'Invoice is not generated due to already have pending payment of mentoring.';
            } else {
                // Create a fresh invoice only when NO pending mentoring invoice exists
                $service = Service::active()->where('name', 'Mentoring Session')->first()
                    ?: Service::active()->byCategory('mentoring')->orderBy('price')->first();
                if ($service) {
                    DB::beginTransaction();
                    try {
                        $invoice = new Invoice();
                        $invoice->user_id = $user->id;
                        $invoice->invoice_number = $invoice->generateInvoiceNumber();
                        $invoice->issued_at = now();
                        $invoice->status = 'pending';
                        $invoice->billing_details = [
                            'context' => 'mentor_share',
                            'mentor_id' => $mentor->id,
                            'mentor_name' => $mentor->name,
                            'user_name' => $user->name,
                            'note' => 'Invoice generated on report share to mentor',
                        ];
                        $invoice->total_amount = $service->price;
                        $invoice->save();

                        $invoice->items()->create([
                            'service_id' => $service->id,
                            'service_name' => $service->name,
                            'unit_price' => $service->price,
                            'quantity' => 1,
                            'total_price' => $service->price,
                            'item_details' => [
                                'context' => 'mentor_share',
                                'mentor_id' => $mentor->id,
                                'mentor_name' => $mentor->name,
                            ],
                        ]);

                        DB::commit();
                        // New invoice created
                        $message = 'Shared with mentor successfully. An invoice has been generated for mentoring.';
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        \Log::error('Failed creating mentor-share invoice on alreadyShared: '.$e->getMessage());
                    }
                }
            }
        }

        // Send email to mentor (immediate send to avoid stale queued payloads)
        Mail::to($mentor->email)->send(new MentorShareMail($user));

        // If we have an invoice, send the user straight to checkout
        if ($invoice) {
            $redirectUrl = route('billing.checkout', $invoice);
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'ok',
                    'message' => $message,
                    'redirect' => $redirectUrl,
                ]);
            }
            return redirect()->to($redirectUrl)->with('success', $message);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'message' => $message]);
        }
        return back()->with('success', $message);
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


