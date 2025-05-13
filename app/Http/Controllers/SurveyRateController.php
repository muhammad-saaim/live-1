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

        // Get unanswered question IDs
        $answeredQuestionIds = $user->usersSurveysRates()->where('survey_id', $survey->id)->pluck('question_id', 'evaluatee_id')->toArray();
        
        // Get unanswered questions
        $unansweredQuestions = $questions->whereNotIn('id', $answeredQuestionIds);

        // Get the answered survey rates to show the user's responses
        $usersurvey = UsersSurveysRate::with('user', 'survey', 'question', 'option')
            ->where('survey_id', $request->survey_id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get();

        if ($unansweredQuestions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'All questions are completed.');
        }

        return view('survey.rate', compact('survey', 'unansweredQuestions', 'usersurvey', 'groupUsers'));
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

        if (!$nextQuestion) {
            // update user's survey status is_completed to true
            /*
             * public function surveys(): BelongsToMany
                {
                    return $this->belongsToMany(Survey::class, 'users_surveys')
                        ->withPivot('is_completed')
                        ->withTimestamps();  // Include created_at and updated_at
                }
             * */
            $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);

            return response()->json(['status' => 'success', 'message' => 'All questions completed. Thank you!']);
        }

        return response()->json(['status' => 'success', 'message' => 'Answer saved. Loading next question...']);
    }

    public function submitGroupAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'evaluatee_id' => 'required|exists:users,id',
            'options_id' => 'required|exists:options,id',
        ]);

        UsersSurveysRate::create([
            'user_id' => Auth::id(),
            'evaluatee_id' => $request->evaluatee_id, 
            'question_id' => $request->question_id,
            'options_id' => $request->options_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted for user ID ' . $request->evaluatee_id,
        ]);
    }


    public function ShowSurvey($survey_id){

    }


}
