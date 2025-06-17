<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use App\Models\Group;
use App\Models\UsersSurveysRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyRateController extends Controller
{
    public function rate(Request $request)
    {
        
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'group_id' => 'nullable',
        ]);

        $survey = Survey::find($request->survey_id);
        $questions = $survey->questions;

        $groupUsers = [];

        if ($request->group_id) {
            $group = Group::find($request->group_id);
            $groupUsers = $group ? $group->users : [];
        }

        if ($questions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'No questions found for this survey.');
        }

        $user = auth()->user();

        // Get all question IDs for this survey
        $questionIds = $questions->pluck('id')->toArray();

        // Get all group user IDs except the current user
        $groupUserIds = collect($groupUsers)->pluck('id')->filter(fn($id) => $id != $user->id)->toArray();

        // Get all rates by the current user for this survey
        $allRates = UsersSurveysRate::where('survey_id', $survey->id)
            ->where('users_id', $user->id)
            ->get();

        // Find questions where self-evaluation is missing
        $selfUnanswered = array_diff(
            $questionIds,
            $allRates->where('evaluatee_id', $user->id)->pluck('question_id')->toArray()
        );

        // Find questions where any group member is not rated
        $groupUnanswered = [];
        foreach ($questionIds as $qid) {
            foreach ($groupUserIds as $gid) {
                if (!$allRates->where('evaluatee_id', $gid)->where('question_id', $qid)->count()) {
                    $groupUnanswered[] = $qid;
                    break; // Only need to know at least one group member is missing
                }
            }
        }

        // Union of both
        $unansweredQuestionIds = array_unique(array_merge($selfUnanswered, $groupUnanswered));
        $unansweredQuestions = $questions->whereIn('id', $unansweredQuestionIds);

        // Get the answered survey rates to show the user's responses
        $usersurvey = UsersSurveysRate::with('user', 'survey', 'question', 'option')
            ->where('survey_id', $request->survey_id)
            ->where('users_id', $user->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get();

        // Get previous self-evaluation answers keyed by question_id
        $selfAnswers = $allRates->where('evaluatee_id', $user->id)->keyBy('question_id');

        if ($unansweredQuestions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'All questions are completed.');
        }

        return view('survey.rate', compact('survey', 'unansweredQuestions', 'usersurvey', 'groupUsers', 'selfAnswers', 'request'));
    }

    public function getNextQuestion(Request $request)
    {
        
        $survey = Survey::find($request->survey_id);
        $questions = $survey->questions;

        // Find the next unanswered question
        $unansweredQuestion = $questions->whereNotIn('id', auth()->user()->questions()
            ->wherePivot('is_completed', 1)
            ->pluck('question_id'))
            ->first();

        if (!$unansweredQuestion) {
            return response()->json(['message' => 'All questions are completed.'], 404);
        }


        return response()->json([
            'question' => $unansweredQuestion->question,
            'options' => $unansweredQuestion->options
        ]);
    }

    public function getPreviousQuestion(Request $request)
    {
        $question = Question::find($request->question_id);

        if (!$question) {
            return response()->json(['message' => 'Question not found.'], 404);
        }

        return response()->json([
            'question' => $question->question,
            'options' => $question->options
        ]);
    }


    public function saveAnswer(Request $request)
    {
        // Save user's answer and mark the question as completed
        auth()->user()->questions()->updateExistingPivot(
            $request->question_id,
            ['is_completed' => 1]
        );

        return response()->json(['message' => 'Answer saved successfully.']);
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'question_id' => 'required|exists:questions,id',
            'options_id' => 'required|exists:question_options,id',
            'evaluatee_id' => 'required|exists:users,id', 
        ]);

        $user = auth()->user();

        // Prevent duplicate answers
        $exists = UsersSurveysRate::where([
            'users_id' => $user->id,
            'evaluatee_id' => $request->evaluatee_id,
            'question_id' => $request->question_id,
            'survey_id' => $request->survey_id,
        ])->exists();
        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'This question is already answered.'], 409);
        }

        // Save response
        UsersSurveysRate::create([
            'users_id' => $user->id,
            'evaluatee_id' => $request->evaluatee_id,
            'survey_id' => $request->survey_id,
            'question_id' => $request->question_id,
            'options_id' => $request->options_id,
        ]);

        // Fetch unanswered question
        $answeredQuestionIds = UsersSurveysRate::where([
            ['users_id', '=', $user->id],
            ['evaluatee_id', '=', $request->evaluatee_id],
            ['survey_id', '=', $request->survey_id],
        ])->pluck('question_id')->toArray();

        $nextQuestion = Question::where('survey_id', $request->survey_id)
            ->whereNotIn('id', $answeredQuestionIds)
            ->first();

        // --- New logic: Only mark as completed if all self and group evaluations are done ---
        // Get all question IDs for this survey
        $questionIds = Question::where('survey_id', $request->survey_id)->pluck('id')->toArray();

        // Get all group user IDs except the current user
        $groupUsers = [];
        if ($request->group_id) {
            $group = \App\Models\Group::find($request->group_id);
            $groupUsers = $group ? $group->users : [];
        }
        $groupUserIds = collect($groupUsers)->pluck('id')->filter(fn($id) => $id != $user->id)->toArray();

        // Get all rates by the current user for this survey
        $allRates = UsersSurveysRate::where('survey_id', $request->survey_id)
            ->where('users_id', $user->id)
            ->get();

        // Find questions where self-evaluation is missing
        $selfUnanswered = array_diff(
            $questionIds,
            $allRates->where('evaluatee_id', $user->id)->pluck('question_id')->toArray()
        );

        // Find questions where any group member is not rated
        $groupUnanswered = [];
        foreach ($questionIds as $qid) {
            foreach ($groupUserIds as $gid) {
                if (!$allRates->where('evaluatee_id', $gid)->where('question_id', $qid)->count()) {
                    $groupUnanswered[] = $qid;
                    break;
                }
            }
        }

        // If there are no unanswered self or group questions, mark as completed
        if (empty($selfUnanswered) && empty($groupUnanswered)) {
            $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);
            return response()->json(['status' => 'success', 'message' => 'All questions completed. Thank you!']);
        }
        // --- End new logic ---

        if (!$nextQuestion) {
            // (Old logic, now only fallback)
            // $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);
            return response()->json(['status' => 'success', 'message' => 'Answer saved. Loading next question...']);
        }

        return response()->json(['status' => 'success', 'message' => 'Answer saved. Loading next question...']);
    }

    public function submitGroupAnswer(Request $request)
    {
        try {
            $request->validate([
                'question_id' => 'required|exists:questions,id',
                'evaluatee_id' => 'required|exists:users,id',
                'options_id' => 'required|exists:question_options,id',
                'survey_id' => 'required|exists:surveys,id',
            ]);

            $user = auth()->user();

            // Prevent duplicate answers
            $exists = UsersSurveysRate::where([
                'users_id' => $user->id,
                'evaluatee_id' => $request->evaluatee_id,
                'question_id' => $request->question_id,
                'survey_id' => $request->survey_id,
            ])->exists();
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This question is already answered for this user.'
                ], 409);
            }

            // Create new rating
            UsersSurveysRate::create([
                'users_id' => $user->id,
                'evaluatee_id' => $request->evaluatee_id, 
                'question_id' => $request->question_id,
                'options_id' => $request->options_id,
                'survey_id' => $request->survey_id,
            ]);

            // Check if all group members have been rated for this question
            $groupUsers = [];
            if ($request->has('group_id')) {
                $group = \App\Models\Group::find($request->group_id);
                $groupUsers = $group ? $group->users : [];
            }

            // If no group context, just return success
            if (empty($groupUsers)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Answer submitted for user ID ' . $request->evaluatee_id,
                ]);
            }

            // Get all group user IDs (including self)
            $groupUserIds = $groupUsers->pluck('id')->toArray();

            // Get all ratings by current user for this specific question
            $questionRatings = UsersSurveysRate::where([
                'users_id' => $user->id,
                'question_id' => $request->question_id,
                'survey_id' => $request->survey_id,
            ])->pluck('evaluatee_id')->toArray();

            // Check if all group members have been rated for this question
            $allRated = true;
            foreach ($groupUserIds as $groupId) {
                if (!in_array($groupId, $questionRatings)) {
                    $allRated = false;
                    break;
                }
            }

            if ($allRated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'All group members rated for this question. Question completed!',
                    'question_completed' => true
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Answer submitted for user ID ' . $request->evaluatee_id,
                'question_completed' => false
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Group Answer Submission Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the answer'
            ], 500);
        }
    }


    public function ShowSurvey($survey_id){

    }


}
