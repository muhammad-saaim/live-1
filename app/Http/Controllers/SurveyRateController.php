<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use App\Models\UsersSurveysRate;
use Illuminate\Http\Request;

class SurveyRateController extends Controller
{
    public function rate(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
        ]);
        $survey = Survey::find($request->survey_id);
        $questions = $survey->questions;

        if($questions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'No questions found for this survey.');
        }
        $user = auth()->user();
        // Check if user has already completed the survey
        $userSurvey = $user->usersSurveysRates()->where('survey_id', $survey->id)->count();

        if ($userSurvey === $survey->questions->count()) {
            $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);
            return redirect()->route('dashboard.index')->with('error', 'All questions are completed.');
        }

        // Get unanswered question IDs
        $answeredQuestionIds = $user->usersSurveysRates()->where('survey_id', $survey->id)->pluck('question_id')->toArray();
        
        // Get unanswered questions
        $unansweredQuestions = $questions->whereNotIn('id', $answeredQuestionIds);
        // dd($unansweredQuestions);
        $usersurvey = UsersSurveysRate::with('user','survey','question','option')->where('survey_id',$request->survey_id)->where('question_id',$unansweredQuestions->first()->id)->get();
        // dd($usersurvey);
        if ($unansweredQuestions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'All questions are completed.');
        }
        //dd($unansweredQuestions);

        return view('survey.rate', compact('survey', 'unansweredQuestions','usersurvey'));
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
        ]);

        $user = auth()->user();

        // Save response
        UsersSurveysRate::create([
            'users_id' => $user->id,
            'survey_id' => $request->survey_id,
            'question_id' => $request->question_id,
            'options_id' => $request->options_id,
        ]);

        // Fetch unanswered question
        $answeredQuestionIds = $user->usersSurveysRates()
            ->where('survey_id', $request->survey_id)
            ->pluck('question_id')->toArray();

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


    public function ShowSurvey($survey_id){

    }


}
