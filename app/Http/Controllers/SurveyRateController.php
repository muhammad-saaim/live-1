<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use App\Models\Group;
use App\Models\User;
use App\Models\UsersSurveysRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyRateController extends Controller
{
    public function rate(Request $request)
    {

        // dd($request);
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'group_id' => 'nullable',
        ]);

        $survey = Survey::find($request->survey_id);
        $questions = $survey->questions;

        $groupUsers = [];

        if ($request->group_id) {
            $group = Group::find($request->group_id);
            $groupUsers = $group ? $group->users->sortByDesc(function ($user) {
                return $user->id === Auth::id(); // Auth user will get `true` (1), others `false` (0)
            })->values() : collect();
        }
        // dd($groupUsers);

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
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->get();

        // Check for unanswered questions based on context
        if ($request->group_id) {
            // Group context: only check group evaluation (self-evaluation disabled)
        $groupUnanswered = [];
        foreach ($questionIds as $qid) {
            foreach ($groupUserIds as $gid) {
                if (!$allRates->where('evaluatee_id', $gid)->where('question_id', $qid)->count()) {
                    $groupUnanswered[] = $qid;
                    break; // Only need to know at least one group member is missing
                }
            }
            }
            $unansweredQuestionIds = array_unique($groupUnanswered);
        } else {
            // Individual context: check self-evaluation
            $selfUnanswered = array_diff(
                $questionIds,
                $allRates->where('evaluatee_id', $user->id)->pluck('question_id')->toArray()
            );
            $unansweredQuestionIds = array_unique($selfUnanswered);
        }
        
        $unansweredQuestions = $questions->whereIn('id', $unansweredQuestionIds);

        // Get the answered survey rates to show the user's responses
        $usersurvey = UsersSurveysRate::with('user', 'survey', 'question', 'option')
            ->where('survey_id', $request->survey_id)
            ->where('users_id', $user->id)
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->whereIn('question_id', $questions->pluck('id'))
            ->get();

        // Get self-answers based on context
        if ($request->group_id) {
            // Group context: no self answers needed
            $selfAnswers = collect();
        } else {
            // Individual context: get self answers
        $selfAnswers = $allRates->where('evaluatee_id', $user->id)->keyBy('question_id');
        }

        if ($unansweredQuestions->isEmpty()) {
            if ($request->group_id) {
                return redirect()->route('group.show', ['group' => $request->group_id])->with('error', 'All questions are completed.');
            } else {
                return redirect()->route('dashboard.index')->with('error', 'All questions are completed.');
            }
        }

        return view('survey.rate', compact('survey', 'unansweredQuestions', 'usersurvey', 'groupUsers', 'selfAnswers', 'request'));
    }

    public function getNextQuestion(Request $request)
{
    \Log::info('Request Data:', $request->all());

    \Log::info("✅ getNextQuestion is being called");

    try {
        $survey = Survey::findOrFail($request->survey_id);
        $questions = $survey->questions;

        $answeredQuestionIds = \App\Models\UsersSurveysRate::where('users_id', auth()->id())
            ->where('survey_id', $survey->id)
            ->pluck('question_id');

        $unansweredQuestion = $questions->whereNotIn('id', $answeredQuestionIds)->first();

        if (!$unansweredQuestion) {
            return response()->json(['message' => 'All questions are completed.'], 404);
        }

        return response()->json([
            'question' => $unansweredQuestion->question,
            'options' => $unansweredQuestion->options
        ]);
    } catch (\Exception $e) {
        \Log::error('Next Question Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error'], 500);
    }
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
        auth()->user()->surveys()->updateExistingPivot(
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

        // Prevent self-evaluation only in group context
        if ($request->group_id && $request->evaluatee_id == $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Self-evaluation is disabled in group context. Please evaluate other group members only.'
            ], 403);
        }

        // Check if this rating already exists
        $exists = UsersSurveysRate::where([
            'users_id' => $user->id,
            'evaluatee_id' => $request->evaluatee_id,
            'question_id' => $request->question_id,
            'survey_id' => $request->survey_id,
        ])
        ->when($request->group_id, function($query) use ($request) {
            $query->where('group_id', $request->group_id);
        }, function($query) {
            $query->whereNull('group_id');
        })
        ->exists();
        
        if ($exists) {
            // Silently skip existing rating and return success
            return response()->json(['status' => 'success', 'message' => 'This question is already answered, skipped.', 'skipped' => true]);
        }

        // Save response
        UsersSurveysRate::create([
            'users_id' => $user->id,
            'evaluatee_id' => $request->evaluatee_id,
            'survey_id' => $request->survey_id,
            'question_id' => $request->question_id,
            'options_id' => $request->options_id,
            'group_id' => $request->group_id ?? null,
        ]);

        // Fetch unanswered question
        $answeredQuestionIds = UsersSurveysRate::where([
            ['users_id', '=', $user->id],
            ['evaluatee_id', '=', $request->evaluatee_id],
            ['survey_id', '=', $request->survey_id],
        ])
        ->when($request->group_id, function($query) use ($request) {
            $query->where('group_id', $request->group_id);
        }, function($query) {
            $query->whereNull('group_id');
        })
        ->pluck('question_id')
        ->toArray();

        $nextQuestion = Question::where('survey_id', $request->survey_id)
            ->whereNotIn('id', $answeredQuestionIds)
            ->first();

        // --- New logic: Only mark as completed if all self and group evaluations are done ---
        // Get all question IDs for this survey
        $questionIds = Question::where('survey_id', $request->survey_id)->pluck('id')->toArray();

        // Get all rates by the current user for this survey
        $allRates = UsersSurveysRate::where('survey_id', $request->survey_id)
            ->where('users_id', $user->id)
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->get();

        if ($request->group_id) {
            // Group context: only check group evaluation (self-evaluation disabled)
            $groupUsers = [];
            $group = \App\Models\Group::find($request->group_id);
            $groupUsers = $group ? $group->users : [];
            $groupUserIds = collect($groupUsers)->pluck('id')->filter(fn($id) => $id != $user->id)->toArray();

        $groupUnanswered = [];
        foreach ($questionIds as $qid) {
            foreach ($groupUserIds as $gid) {
                if (!$allRates->where('evaluatee_id', $gid)->where('question_id', $qid)->count()) {
                    $groupUnanswered[] = $qid;
                    break;
                }
            }
        }

            // If there are no unanswered group questions, mark as completed
            if (empty($groupUnanswered)) {
                $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);
                return response()->json(['status' => 'success', 'message' => 'All questions completed. Thank you!']);
            }
        } else {
            // Individual context: check self-evaluation
            $selfUnanswered = array_diff(
                $questionIds,
                $allRates->where('evaluatee_id', $user->id)->pluck('question_id')->toArray()
            );

            // If there are no unanswered self questions, mark as completed
            if (empty($selfUnanswered)) {
            $user->surveys()->updateExistingPivot($request->survey_id, ['is_completed' => 1]);
            return response()->json(['status' => 'success', 'message' => 'All questions completed. Thank you!']);
            }
        }
        // --- End new logic ---

        if (!$nextQuestion) {
            // No more questions left
            return response()->json([
                'status' => 'success',
                'message' => 'All questions completed. Thank you!',
                'next_question' => null
            ]);
        }

        // Prepare next question object
        $options = $nextQuestion->options->map(function($option) {
            return [
                'id' => $option->id,
                'name' => $option->name
            ];
        })->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted for user ID ' . $request->evaluatee_id,
            'next_question' => [
                'id' => $nextQuestion->id,
                'question' => $nextQuestion->question,
                'options' => $options
            ]
        ]);

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
            $evaluatee = User::findOrFail($request->evaluatee_id);

            // Check if this rating already exists
            $exists = UsersSurveysRate::where([
                'users_id' => $user->id,
                'evaluatee_id' => $request->evaluatee_id,
                'question_id' => $request->question_id,
                'survey_id' => $request->survey_id,
            ])
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->exists();
            
            if ($exists) {
                // Silently skip existing rating and return success
                return response()->json([
                    'status' => 'success',
                    'message' => 'Rating already exists for this user, skipped.',
                    'skipped' => true
                ]);
            }

            // Create new rating
            UsersSurveysRate::create([
                 'users_id' => $user->id,
                'evaluatee_id' => $request->evaluatee_id, 
                'question_id' => $request->question_id,
                'options_id' => $request->options_id,
                'survey_id' => $request->survey_id,
                'group_id' => $request->group_id ?? null,
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
                    'message' => 'Answer submitted for ' . $evaluatee->name,
                ]);
            }

            // Get all group user IDs (including self)
            $groupUserIds = $groupUsers->pluck('id')->toArray();

            // Get all ratings by current user for this specific question
            $questionRatings = UsersSurveysRate::where([
                'users_id' => $user->id,
                'question_id' => $request->question_id,
                'survey_id' => $request->survey_id,
            ])
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->pluck('evaluatee_id')
            ->toArray();

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

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Answer submitted for user ID ' . $request->evaluatee_id,
            //     'question_completed' => false
            // ]);
            
return response()->json([
    'status' => 'success',
    'url' => route('rate.survey'), // will redirect to this GET route
    'data' => [
        'group_id' => $request->group_id,
        'survey_id' => $request->survey_id,
    ]
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

    public function checkExistingRatings(Request $request)
    {
        try {
            $request->validate([
                'question_id' => 'required|exists:questions,id',
                'survey_id' => 'required|exists:surveys,id',
                'group_id' => 'nullable|exists:groups,id',
            ]);

            $user = auth()->user();
            $questionId = $request->question_id;
            $surveyId = $request->survey_id;

            // Get group users if group_id is provided
            $groupUsers = [];
            if ($request->group_id) {
                $group = \App\Models\Group::find($request->group_id);
                $groupUsers = $group ? $group->users : [];
            }

            // If no group context, check only self-rating
            if (empty($groupUsers)) {
                $selfExists = UsersSurveysRate::where([
                    'users_id' => $user->id,
                    'evaluatee_id' => $user->id,
                    'question_id' => $questionId,
                    'survey_id' => $surveyId,
                ])
                ->when($request->group_id, function($query) use ($request) {
                    $query->where('group_id', $request->group_id);
                }, function($query) {
                    $query->whereNull('group_id');
                })
                ->exists();

                return response()->json([
                    'status' => 'success',
                    'all_existing' => $selfExists,
                    'existing_count' => $selfExists ? 1 : 0,
                    'total_count' => 1
                ]);
            }

            // Get all group user IDs (including self)
            $groupUserIds = $groupUsers->pluck('id')->toArray();

            // Get existing ratings for this question by current user
            $existingRatings = UsersSurveysRate::where([
                'users_id' => $user->id,
                'question_id' => $questionId,
                'survey_id' => $surveyId,
            ])
            ->when($request->group_id, function($query) use ($request) {
                $query->where('group_id', $request->group_id);
            }, function($query) {
                $query->whereNull('group_id');
            })
            ->pluck('evaluatee_id')
            ->toArray();

            $existingCount = count($existingRatings);
            $totalCount = count($groupUserIds);
            $allExisting = $existingCount === $totalCount;

            return response()->json([
                'status' => 'success',
                'all_existing' => $allExisting,
                'existing_count' => $existingCount,
                'total_count' => $totalCount,
                'existing_user_ids' => $existingRatings
            ]);

        } catch (\Exception $e) {
            \Log::error('Check Existing Ratings Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking existing ratings'
            ], 500);
        }
    }

    public function ShowSurvey($survey_id){

    }


}
