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
                return $user->id === Auth::id(); // Auth user will get true (1), others false (0)
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

//     public function getNextQuestion(Request $request)
// {
//     \Log::info('Request Data:', $request->all());

//     \Log::info("✅ getNextQuestion is being called");

//     try {
//         $survey = Survey::findOrFail($request->survey_id);
//         $questions = $survey->questions;

//         $answeredQuestionIds = \App\Models\UsersSurveysRate::where('users_id', auth()->id())
//             ->where('survey_id', $survey->id)
//             ->pluck('question_id');

//         $unansweredQuestion = $questions->whereNotIn('id', $answeredQuestionIds)->first();

//         if (!$unansweredQuestion) {
//             return response()->json(['message' => 'All questions are completed.'], 404);
//         }

//         return response()->json([
//             'question' => $unansweredQuestion->question,
//             'options' => $unansweredQuestion->options
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Next Question Error: ' . $e->getMessage());
//         return response()->json(['error' => 'Server Error'], 500);
//     }
// }

    // public function getPreviousQuestion(Request $request)
    // {
    //     $question = Question::find($request->question_id);

    //     if (!$question) {
    //         return response()->json(['message' => 'Question not found.'], 404);
    //     }

    //     return response()->json([
    //         'question' => $question->question,
    //         'options' => $question->options
    //     ]);
    // }


    public function getNextQuestion(Request $request)
{
    try {
        $currentQuestionId = $request->question_id;
        $selectedOptionId = $request->selected_option_id;
        $userId = auth()->id();
\Log::info('📝 Incoming POST data', [
        'question_id' => $currentQuestionId,
        'selected_option_id' => $selectedOptionId
    ]);
        if (!$currentQuestionId) {
            return response()->json(['message' => 'Question ID is required.'], 400);
        }

        if (!$selectedOptionId) {
            return response()->json(['message' => 'Please select an answer before moving to the next question.'], 400);
        }

        if (!$userId) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Find current question
        $currentQuestion = Question::find($currentQuestionId);
        if (!$currentQuestion) {
            return response()->json(['message' => 'Current question not found.'], 404);
        }

        $surveyId = $currentQuestion->survey_id;

        // Save/update user’s answer for the current question
        

        // Find the next question (could be rated or unrated)
        $nextQuestion = Question::where('id', '>', $currentQuestionId)
            ->where('survey_id', $surveyId)
            ->with([
                'options',
                'usersSurveysRates' => function ($query) use ($userId) {
                    $query->where('users_id', $userId);
                }
            ])
            ->orderBy('id', 'asc')
            ->first();

        if (!$nextQuestion) {
            return response()->json(['question' => null]); // No more questions
        }

        // Map options with points
        $options = $nextQuestion->options->map(function ($option) {
            return [
                'id' => $option->id,
                'name' => $option->name,
                'point' => $option->point
            ];
        });

        // Get user's existing rating for next question (if any)
        $userRate = $nextQuestion->usersSurveysRates->first();
        $selectedOption = $nextQuestion->options->where('id', $userRate ? $userRate->options_id : 0)->first();
        $points = $selectedOption ? $selectedOption->point : null;

        // Get current question index and total count
        $allQuestions = Question::where('survey_id', $surveyId)->orderBy('id', 'asc')->get();
        $currentIndex = $allQuestions->search(function($q) use ($nextQuestion) {
            return $q->id === $nextQuestion->id;
        }) + 1;
        $totalCount = $allQuestions->count();

        // Mark pre-rated options if question is already rated
        $preRatedOptions = [];
        if ($userRate) {
            $preRatedOptions[] = $userRate->options_id;
        }

        \Log::info("User {$userId} answered Q{$currentQuestionId} with Option {$selectedOptionId}, moving to Q{$nextQuestion->id}");

        return response()->json([
            'question' => [
                'id' => $nextQuestion->id,
                'question' => $nextQuestion->question,
                'description' => $nextQuestion->description ?? '',
                'options' => $options,
                'user_rating' => $userRate ? [
                    'options_id' => $userRate->options_id,
                    'evaluatee_id' => $userRate->evaluatee_id,
                    'points' => $points
                ] : null,
                'pre_rated_options' => $preRatedOptions
            ],
            'current_index' => $currentIndex,
            'total_count' => $totalCount
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in getNextQuestion: ' . $e->getMessage());
        return response()->json(['message' => 'Server error occurred'], 500);
    }
}

      public function getPreviousQuestion(Request $request)
    {
        try {
            $currentQuestionId = $request->query('question_id');
            $surveyId = $request->query('survey_id');
            $groupId = $request->query('group_id');
            $userId = auth()->id();

            if (!$currentQuestionId) {
                return response()->json(['message' => 'Question ID is required.'], 400);
            }

            if (!$userId) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            $currentQuestion = Question::find($currentQuestionId);
            if (!$currentQuestion) {
                return response()->json(['message' => 'Current question not found.'], 404);
            }

            $surveyId = $currentQuestion->survey_id;

            // Find the previous question that has been rated
            if ($groupId) {
                // For group evaluation, find the previous question that has been rated by the current user for this group
                $previousQuestion = Question::where('id', '<', $currentQuestionId)
                    ->where('survey_id', $surveyId)
                    ->whereHas('usersSurveysRates', function ($query) use ($userId, $groupId) {
                        $query->where('users_id', $userId)
                              ->where('group_id', $groupId);
                    })
                    ->orderBy('id', 'desc')
                    ->with([
                        'options',
                        'usersSurveysRates' => function ($query) use ($userId, $groupId) {
                            $query->where('users_id', $userId)
                                  ->where('group_id', $groupId);
                        }
                    ])
                    ->first();
            } else {
                // For self-evaluation, find the previous question that has been rated by the current user
                $previousQuestion = Question::where('id', '<', $currentQuestionId)
                    ->where('survey_id', $surveyId)
                    ->whereHas('usersSurveysRates', function ($query) use ($userId) {
                        $query->where('users_id', $userId)
                              ->whereNull('group_id');
                    })
                    ->orderBy('id', 'desc')
                    ->with([
                        'options',
                        'usersSurveysRates' => function ($query) use ($userId) {
                            $query->where('users_id', $userId)
                                  ->whereNull('group_id');
                        }
                    ])
                    ->first();
            }

            if (!$previousQuestion) {
                return response()->json(['question' => null]);
            }

            // Map options: id, name, point
            $options = $previousQuestion->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'point' => $option->point,
                ];
            });

            // Get user's selected rate
            $userRate = $previousQuestion->usersSurveysRates->first();

            // Find the option to get points for logging
            $selectedOption = $previousQuestion->options->where('id', $userRate ? $userRate->options_id : 0)->first();
            $points = $selectedOption ? $selectedOption->point : null;

            // For group evaluation, get all group users and their ratings
            $groupUsers = [];
            $preRatedOptions = [];
            
            if ($groupId) {
                $group = Group::find($groupId);
                if ($group) {
                    $groupUsers = $group->users->where('id', '!=', $userId)->values();
                    
                    // Get all ratings for this question by the current user for group members
                    $groupRatings = UsersSurveysRate::where('question_id', $previousQuestion->id)
                        ->where('survey_id', $surveyId)
                        ->where('users_id', $userId)
                        ->where('group_id', $groupId)
                        ->get();
                    
                    // Mark pre-rated options
                    foreach ($groupRatings as $rating) {
                        $preRatedOptions[] = [
                            'user_id' => $rating->evaluatee_id,
                            'option_id' => $rating->options_id
                        ];
                    }
                }
            }

            // Log user rating with points
            \Log::info("User {$userId} moving from Q{$currentQuestionId} to previous Q{$previousQuestion->id} (Group: {$groupId})");

            $response = [
                'question' => [
                    'id' => $previousQuestion->id,
                    'question' => $previousQuestion->question,
                    'description' => $previousQuestion->description ?? '',
                    'options' => $options,
                    'user_rating' => $userRate ? [
                        'options_id' => $userRate->options_id,
                        'evaluatee_id' => $userRate->evaluatee_id,
                        'points' => $points,
                    ] : null,
                ],
                'debug_info' => [
                    'current_question_id' => $currentQuestionId,
                    'previous_question_id' => $previousQuestion->id,
                    'group_id' => $groupId,
                    'user_id' => $userId
                ]
            ];

            // Add group-specific data if this is a group evaluation
            if ($groupId) {
                $response['question']['group_users'] = $groupUsers;
                $response['question']['pre_rated_options'] = $preRatedOptions;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error in getPreviousQuestion: ' . $e->getMessage());
            return response()->json(['message' => 'Server error occurred'], 500);
        }
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
            \Log::info('submitAnswer called with data:', $request->all());

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
            'message' => 'Answer submitted  ',
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
                    'message' => 'Answer submitted ',
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

    public function getNextRatedQuestion(Request $request)
    {
        try {
            $request->validate([
                'current_question_id' => 'required|exists:questions,id',
                'survey_id' => 'required|exists:surveys,id',
                'group_id' => 'nullable|exists:groups,id',
            ]);

            $currentQuestionId = $request->current_question_id;
            $surveyId = $request->survey_id;
            $userId = auth()->id();

            if (!$userId) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            // Find the next rated question (already answered by user) in sequence
            $nextRatedQuestion = Question::where('id', '>', $currentQuestionId)
                ->where('survey_id', $surveyId)
                ->whereHas('usersSurveysRates', function ($query) use ($userId, $request) {
                    $query->where('users_id', $userId);
                    if ($request->group_id) {
                        $query->where('group_id', $request->group_id);
                    } else {
                        $query->whereNull('group_id');
                    }
                })
                ->with([
                    'options',
                    'usersSurveysRates' => function ($query) use ($userId, $request) {
                        $query->where('users_id', $userId);
                        if ($request->group_id) {
                            $query->where('group_id', $request->group_id);
                        } else {
                            $query->whereNull('group_id');
                        }
                    }
                ])
                ->orderBy('id', 'asc')
                ->first();

            if (!$nextRatedQuestion) {
                return response()->json(['question' => null]); // No more rated questions
            }

            // Map options with points
            $options = $nextRatedQuestion->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'point' => $option->point
                ];
            });

            // Get user's existing rating for next rated question
            $userRate = $nextRatedQuestion->usersSurveysRates->first();
            $selectedOption = $nextRatedQuestion->options->where('id', $userRate ? $userRate->options_id : 0)->first();
            $points = $selectedOption ? $selectedOption->point : null;

            // Get current question index and total count
            $allQuestions = Question::where('survey_id', $surveyId)->orderBy('id', 'asc')->get();
            $currentIndex = $allQuestions->search(function($q) use ($nextRatedQuestion) {
                return $q->id === $nextRatedQuestion->id;
            }) + 1;
            $totalCount = $allQuestions->count();

            // For group evaluation, get all group users and their ratings
            $groupUsers = [];
            $preRatedOptions = [];
            
            if ($request->group_id) {
                $group = Group::find($request->group_id);
                if ($group) {
                    $groupUsers = $group->users->where('id', '!=', $userId)->values();
                    
                    // Get all ratings for this question by the current user for group members
                    $groupRatings = UsersSurveysRate::where('question_id', $nextRatedQuestion->id)
                        ->where('survey_id', $surveyId)
                        ->where('users_id', $userId)
                        ->where('group_id', $request->group_id)
                        ->get();
                    
                    // Mark pre-rated options
                    foreach ($groupRatings as $rating) {
                        $preRatedOptions[] = [
                            'user_id' => $rating->evaluatee_id,
                            'option_id' => $rating->options_id
                        ];
                    }
                }
            } else {
                // For self-evaluation, mark pre-rated options
                if ($userRate) {
                    $preRatedOptions[] = $userRate->options_id;
                }
            }

            \Log::info("User {$userId} moving from Q{$currentQuestionId} to next rated Q{$nextRatedQuestion->id}");

            $response = [
                'question' => [
                    'id' => $nextRatedQuestion->id,
                    'question' => $nextRatedQuestion->question,
                    'description' => $nextRatedQuestion->description ?? '',
                    'options' => $options,
                    'user_rating' => $userRate ? [
                        'options_id' => $userRate->options_id,
                        'evaluatee_id' => $userRate->evaluatee_id,
                        'points' => $points
                    ] : null,
                    'pre_rated_options' => $preRatedOptions
                ],
                'current_index' => $currentIndex,
                'total_count' => $totalCount
            ];

            // Add group-specific data if this is a group evaluation
            if ($request->group_id) {
                $response['question']['group_users'] = $groupUsers;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error in getNextRatedQuestion: ' . $e->getMessage());
            return response()->json(['message' => 'Server error occurred'], 500);
        }
    }

    public function checkAllUsersRated(Request $request)
    {
        try {
            $request->validate([
                'question_id' => 'required|exists:questions,id',
                'survey_id' => 'required|exists:surveys,id',
                'group_id' => 'required|exists:groups,id',
            ]);

            $questionId = $request->question_id;
            $surveyId = $request->survey_id;
            $groupId = $request->group_id;
            $userId = auth()->id();

            if (!$userId) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            // Get the group and its users (excluding current user)
            $group = Group::find($groupId);
            if (!$group) {
                return response()->json(['message' => 'Group not found.'], 404);
            }

            $groupUsers = $group->users->where('id', '!=', $userId);
            $totalUsers = $groupUsers->count();

            if ($totalUsers === 0) {
                return response()->json([
                    'all_rated' => true,
                    'message' => 'No other users in group to rate.'
                ]);
            }

            // Get all ratings by current user for this question and group
            $ratings = UsersSurveysRate::where('question_id', $questionId)
                ->where('survey_id', $surveyId)
                ->where('users_id', $userId)
                ->where('group_id', $groupId)
                ->get();

            $ratedUsers = $ratings->count();
            $allRated = $ratedUsers >= $totalUsers;

            \Log::info("Group {$groupId} question {$questionId}: {$ratedUsers}/{$totalUsers} users rated");
            \Log::info("Group users: " . $groupUsers->pluck('id')->implode(','));
            \Log::info("Rated users: " . $ratings->pluck('evaluatee_id')->implode(','));

            return response()->json([
                'all_rated' => $allRated,
                'rated_count' => $ratedUsers,
                'total_count' => $totalUsers,
                'message' => $allRated ? 'All users rated for this question.' : 'Not all users rated yet.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in checkAllUsersRated: ' . $e->getMessage());
            return response()->json(['message' => 'Server error occurred'], 500);
        }
    }

    public function getUnratedQuestions(Request $request)
    {
        try {
            $request->validate([
                'survey_id' => 'required|exists:surveys,id',
                'group_id' => 'nullable|exists:groups,id',
            ]);

            $surveyId = $request->survey_id;
            $userId = auth()->id();

            if (!$userId) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            // Get all questions for the survey
            $allQuestions = Question::where('survey_id', $surveyId)
                ->with('options')
                ->orderBy('id', 'asc')
                ->get();

            // Get questions that haven't been rated by the user
            $ratedQuestionIds = UsersSurveysRate::where('survey_id', $surveyId)
                ->where('users_id', $userId)
                ->when($request->group_id, function($query) use ($request) {
                    $query->where('group_id', $request->group_id);
                }, function($query) {
                    $query->whereNull('group_id');
                })
                ->pluck('question_id')
                ->toArray();

            $unratedQuestions = $allQuestions->whereNotIn('id', $ratedQuestionIds);

            if ($unratedQuestions->isEmpty()) {
                return response()->json(['questions' => null]); // No unrated questions
            }

            // Get the first unrated question
            $firstUnratedQuestion = $unratedQuestions->first();

            // Map options with points
            $options = $firstUnratedQuestion->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'point' => $option->point
                ];
            });

            // Get current question index and total count
            $currentIndex = $allQuestions->search(function($q) use ($firstUnratedQuestion) {
                return $q->id === $firstUnratedQuestion->id;
            }) + 1;
            $totalCount = $allQuestions->count();

            // For group evaluation, get all group users
            $groupUsers = [];
            if ($request->group_id) {
                $group = Group::find($request->group_id);
                if ($group) {
                    $groupUsers = $group->users->where('id', '!=', $userId)->values();
                }
            }

            \Log::info("User {$userId} fetching unrated question Q{$firstUnratedQuestion->id}");

            $response = [
                'questions' => [
                    [
                        'id' => $firstUnratedQuestion->id,
                        'question' => $firstUnratedQuestion->question,
                        'description' => $firstUnratedQuestion->description ?? '',
                        'options' => $options,
                        'pre_rated_options' => []
                    ]
                ],
                'current_index' => $currentIndex,
                'total_count' => $totalCount
            ];

            // Add group-specific data if this is a group evaluation
            if ($request->group_id) {
                $response['questions'][0]['group_users'] = $groupUsers;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error in getUnratedQuestions: ' . $e->getMessage());
            return response()->json(['message' => 'Server error occurred'], 500);
        }
    }

}