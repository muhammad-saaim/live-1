<?php

use App\Models\Type;
use App\Models\User;
use App\Models\Group;
use App\Models\Survey;
use App\Models\Relation;
use App\Models\UserRelative;
use Illuminate\Support\Facades\DB;

if (!function_exists('RandomSecurePassword')) {
    function RandomSecurePassword($lower = 5, $upper = 2, $digits = 2, $special_characters = 1): string
    {
        $lower_case = "abcdefghijklmnopqrstuvwxyz";
        $upper_case = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "1234567890";
        $symbols = "!@#$%^&*";

        $lower_case = str_shuffle($lower_case);
        $upper_case = str_shuffle($upper_case);
        $numbers = str_shuffle($numbers);
        $symbols = str_shuffle($symbols);

        $random_password = substr($lower_case, 0, $lower);
        $random_password .= substr($upper_case, 0, $upper);
        $random_password .= substr($numbers, 0, $digits);
        $random_password .= substr($symbols, 0, $special_characters);

        return str_shuffle($random_password);
    }
}

if (!function_exists('storeInverseRelation')) {
    function storeInverseRelation($invitedId, $inviterId, $relationName)
    {
        $inviter = User::find($inviterId);
    
        if (!$inviter || !$inviter->gender) {
            return; 
        }
    
        // Gender-sensitive inverse map
        $inverseMap = [
            'Father'        => ['male' => 'Son',        'female' => 'Daughter'],
            'Mother'        => ['male' => 'Son',        'female' => 'Daughter'],
            'Son'           => ['male' => 'Father',     'female' => 'Mother'],
            'Daughter'      => ['male' => 'Father',     'female' => 'Mother'],
            'Brother'       => ['male' => 'Brother',    'female' => 'Sister'],
            'Sister'        => ['male' => 'Brother',    'female' => 'Sister'],
            'Uncle'         => ['male' => 'Nephew',     'female' => 'Niece'],
            'Aunt'          => ['male' => 'Nephew',     'female' => 'Niece'],
            'Nephew'        => ['male' => 'Uncle',      'female' => 'Aunt'],
            'Niece'         => ['male' => 'Uncle',      'female' => 'Aunt'],
            'Grandfather'   => ['male' => 'Grandson',   'female' => 'Granddaughter'],
            'Grandmother'   => ['male' => 'Grandson',   'female' => 'Granddaughter'],
            'Grandson'      => ['male' => 'Grandfather','female' => 'Grandmother'],
            'Granddaughter' => ['male' => 'Grandfather','female' => 'Grandmother'],
            'Spouse'        => ['male' => 'Spouse',     'female' => 'Spouse'],
            'Cousin'        => ['male' => 'Cousin',     'female' => 'Cousin'], 
        ];
    
        // Determine inverse relation name based on inviter's gender
        $inverseName = $inverseMap[$relationName][$inviter->gender] ?? null;
    
        if (!$inverseName) {
            return; // Skip if inverse is not defined
        }
    
        $inverseRelation = Relation::where('name', $inverseName)->first();
    
        if (!$inverseRelation) {
            return; // Skip if inverse relation is not found in DB
        }
    
        // Avoid duplicate
        $exists = UserRelative::where([
            'user_id' => $invitedId,
            'relative_id' => $inviterId,
        ])->exists();
    
        if (!$exists) {
            UserRelative::create([
                'user_id' => $invitedId,
                'relative_id' => $inviterId,
                'relation_id' => $inverseRelation->id,
            ]);
        }
    }
}

if (!function_exists('linkNewRelativeWithExistingRelations')) {
    function linkNewRelativeWithExistingRelations($inviterId, $invitedId, $relationToInviter, $invitedGender)
    {
        $inviter = User::find($inviterId);
        $invited = User::find($invitedId);

        if (!$inviter || !$invited) {
            return;
        }

        $existingRelations = UserRelative::where('user_id', $inviterId)->with('relative', 'relation')->get();

        // Define mappings: [existing_relation][new_relation] = relation_to_new_relative
        $relationMap = [
            'Father' => [
                'Brother' => 'Father',
                'Sister' => 'Father',
                'Son' => $invitedGender === 'male' ? 'Grandfather' : 'Grandfather',
                'Daughter' => $invitedGender === 'male' ? 'Grandfather' : 'Grandfather',
            ],
            'Mother' => [
                'Brother' => 'Mother',
                'Sister' => 'Mother',
                'Son' => 'Grandmother',
                'Daughter' => 'Grandmother',
            ],
            'Brother' => [
                'Brother' => 'Brother',
                'Sister' => 'Brother',
            ],
            'Sister' => [
                'Brother' => 'Sister',
                'Sister' => 'Sister',
            ],
            'Son' => [
                'Brother' => 'Nephew',
                'Sister' => 'Nephew',
            ],
            'Daughter' => [
                'Brother' => 'Niece',
                'Sister' => 'Niece',
            ],
            'Cousin' => [
                'Brother' => 'Cousin',
                'Sister' => 'Cousin',
            ],
            'Grandfather' => [
                'Brother' => 'Grandfather',
                'Sister' => 'Grandfather',
            ],
            'Grandmother' => [
                'Brother' => 'Grandmother',
                'Sister' => 'Grandmother',
            ],
        ];

        $inverseMap = [
            'Father' => $invitedGender === 'male' ? 'Son' : 'Daughter',
            'Mother' => $invitedGender === 'male' ? 'Son' : 'Daughter',
            'Brother' => 'Brother',
            'Sister' => 'Sister',
            'Nephew' => $invitedGender === 'male' ? 'Uncle' : 'Aunt',
            'Niece' => $invitedGender === 'male' ? 'Uncle' : 'Aunt',
            'Son' => $invitedGender === 'male' ? 'Brother' : 'Sister',
            'Daughter' => $invitedGender === 'male' ? 'Brother' : 'Sister',
            'Cousin' => 'Cousin',
            'Grandfather' => $invitedGender === 'male' ? 'Grandson' : 'Granddaughter',
            'Grandmother' => $invitedGender === 'male' ? 'Grandson' : 'Granddaughter',
        ];

        foreach ($existingRelations as $relation) {
            $existingRelationName = $relation->relation->name;
            $existingRelativeId = $relation->relative_id;

            if (!isset($relationMap[$existingRelationName][$relationToInviter])) {
                continue;
            }
            
            if ($existingRelativeId === $invitedId) {
                continue;
            }

            $newRelationName = $relationMap[$existingRelationName][$relationToInviter];
            $inverseRelationName = $inverseMap[$newRelationName] ?? null;

            $inverseRelationModel = Relation::where('name', $newRelationName)->first();
            $relationModel = Relation::where('name', $inverseRelationName)->first();

            // Check if the relation already exists before creating
            if ($relationModel) {
                $exists = UserRelative::where([
                    'user_id' => $existingRelativeId,
                    'relative_id' => $invitedId,
                ])->exists();

                if (!$exists) {
                    UserRelative::create([
                        'user_id' => $existingRelativeId,
                        'relative_id' => $invitedId,
                        'relation_id' => $relationModel->id,
                    ]);
                }
            }

            // Check for inverse relation
            if ($inverseRelationModel) {
                $exists = UserRelative::where([
                    'user_id' => $invitedId,
                    'relative_id' => $existingRelativeId,
                ])->exists();

                if (!$exists) {
                    UserRelative::create([
                        'user_id' => $invitedId,
                        'relative_id' => $existingRelativeId,
                        'relation_id' => $inverseRelationModel->id,
                    ]);
                }
            }

        }
    }
}

if (!function_exists('calculateSurveyTypePoints')) {
    function calculateSurveyTypePoints(Group $group)
    {
        // $typeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION'];
        // $typeMap = \App\Models\Type::whereIn('name', $typeNames)->pluck('id', 'name');

        $groupSurveyTypePoints = []; // [survey_id][type_name]['self'|'others']

        // 👇 Initialize grand totals across all surveys
        $grandSelfTotalRatings = 0;
        $grandOthersTotalRatings = 0;
        $grandSelfTotalPoints = 0;
        $grandOthersTotalPoints = 0;

        foreach ($group->defaultSurveys() as $survey) {
            // 👇 Initialize totals for this survey
            $surveySelfTotalRatings = 0;
            $surveyOthersTotalRatings = 0;
            $surveySelfTotalPoints = 0;
            $surveyOthersTotalPoints = 0;

            // Get survey model title (should be string, not id)
$surveyTitle = (int)($survey->model_id ?? 0);

            // Set type names for self and others based on survey model title
            if ($surveyTitle === 1) {
                $selfTypeNames = $othersTypeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION'];
            } elseif ($surveyTitle === 2 || $surveyTitle === 8) {
                $selfTypeNames = ['INTROVERTS', 'RELATIONSHIP', 'SELF-PERCEPTION'];
                $othersTypeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION'];
            } else {
                $selfTypeNames = $othersTypeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION'];
            }
            // Set $typeNames for this survey
            $typeNames = array_unique(array_merge($selfTypeNames, $othersTypeNames));
            $typeMap = \App\Models\Type::whereIn('name', $typeNames)->pluck('id', 'name');
        //    dd($surveyTitle);
            foreach ($typeMap as $typeName => $typeId) {
                // Self rating: only for allowed types
                if (in_array($typeName, $selfTypeNames)) {
                    $selfRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                        ->where('survey_id', $survey->id)
                        ->where('users_id', auth()->id())
                        ->where('evaluatee_id', auth()->id())
                        ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                        ->with('option', 'question')
                        ->get();

                    $selfPoints = $selfRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                    $selfPointCounts = $selfRates->groupBy(fn($rate) => optional($rate->option)->point)->map->count();
                    $selfTotalRatings = $selfPointCounts->sum();

                    // 👇 Add to survey totals
                    $surveySelfTotalRatings += $selfTotalRatings;
                    $surveySelfTotalPoints += $selfPoints;
                } else {
                    $selfPoints = 0;
                    $selfPointCounts = collect();
                    $selfTotalRatings = 0;
                }

                // Others rating: only for allowed types
                if (in_array($typeName, $othersTypeNames)) {
                    $othersRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                        ->where('survey_id', $survey->id)
                        ->where('evaluatee_id', auth()->id())
                        ->where('users_id', '!=', auth()->id())
                        ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                        ->with('option', 'question')
                        ->get();

                    $othersPoints = $othersRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                    $othersPointCounts = $othersRates->groupBy(fn($rate) => optional($rate->option)->point)->map->count();
                    $othersTotalRatings = $othersPointCounts->sum();

                    // 👇 Add to survey totals
                    $surveyOthersTotalRatings += $othersTotalRatings;
                    $surveyOthersTotalPoints += $othersPoints;
                } else {
                    $othersPoints = 0;
                    $othersPointCounts = collect();
                    $othersTotalRatings = 0;
                }

                // Store per type
                $groupSurveyTypePoints[$survey->id][$typeName] = [
                    'self' => [
                        'total_points'  => $selfPoints,
                        'point_counts'  => $selfPointCounts,
                        'total_ratings' => $selfTotalRatings,
                    ],
                    'others' => [
                        'total_points'  => $othersPoints,
                        'point_counts'  => $othersPointCounts,
                        'total_ratings' => $othersTotalRatings,
                    ],
                ];
            }

            // Store per survey totals
            $groupSurveyTypePoints[$survey->id]['totals'] = [
                'self' => [
                    'total_ratings' => $surveySelfTotalRatings,
                    'total_points'  => $surveySelfTotalPoints,
                ],
                'others' => [
                    'total_ratings' => $surveyOthersTotalRatings,
                    'total_points'  => $surveyOthersTotalPoints,
                ],
            ];

            // 👇 Accumulate into grand totals
            $grandSelfTotalRatings += $surveySelfTotalRatings;
            $grandOthersTotalRatings += $surveyOthersTotalRatings;
            $grandSelfTotalPoints += $surveySelfTotalPoints;
            $grandOthersTotalPoints += $surveyOthersTotalPoints;
        }

        // 👇 Add grand totals across all surveys
        $groupSurveyTypePoints['all_surveys_totals'] = [
            'self' => [
                'total_ratings' => $grandSelfTotalRatings,
                'total_points'  => $grandSelfTotalPoints,
            ],
            'others' => [
                'total_ratings' => $grandOthersTotalRatings,
                'total_points'  => $grandOthersTotalPoints,
            ],
        ];
            // dd($groupSurveyTypePoints);
        return $groupSurveyTypePoints;
    }

}

if (!function_exists('calculateSurveyTypetotalPoints')) {
    function calculateSurveyTypetotalPoints(Group $group)
    {
        // 👇 Fetch lowercase group type names
        $groupTypeNames = $group->groupTypes->pluck('name')->map(fn($n) => strtolower($n))->toArray();

        // 👇 Decide which type names to include based on group type
        if (in_array('family', $groupTypeNames)) {
            $typeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION','SOCIAL', 'ACADEMIC'];
        } elseif (in_array('friend', $groupTypeNames)) {
            $typeNames = ['SOCIAL', 'ACADEMIC'];
        } else {
            $typeNames = []; // fallback if needed
        }

        // 👇 Map type names to type IDs
        $typeMap = \App\Models\Type::whereIn('name', $typeNames)->pluck('id', 'name');

        $groupSurveyTypePoints = [];

        $grandSelfTotalRatings = 0;
        $grandOthersTotalRatings = 0;
        $grandSelfTotalPoints = 0;
        $grandOthersTotalPoints = 0;

        // Initialize combined totals per type
        $combinedTotals = [];

        foreach ($group->defaultSurveys() as $survey) {
            $surveySelfTotalRatings = 0;
            $surveyOthersTotalRatings = 0;
            $surveySelfTotalPoints = 0;
            $surveyOthersTotalPoints = 0;

            foreach ($typeMap as $typeName => $typeId) {
                // Self rating
                $selfRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                    ->where('survey_id', $survey->id)
                    ->where('users_id', auth()->id())
                    ->where('evaluatee_id', auth()->id())
                    ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                    ->with('option', 'question')
                    ->get();

                $selfPoints = $selfRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                $selfPointCounts = $selfRates->groupBy(fn($rate) =>
                    optional($rate->option)->point
                )->map->count();
                $selfTotalRatings = $selfPointCounts->sum();

                $surveySelfTotalRatings += $selfTotalRatings;
                $surveySelfTotalPoints += $selfPoints;

                // Others rating
                $othersRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                    ->where('survey_id', $survey->id)
                    ->where('evaluatee_id', auth()->id())
                    ->where('users_id', '!=', auth()->id())
                    ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                    ->with('option', 'question')
                    ->get();

                $othersPoints = $othersRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                $othersPointCounts = $othersRates->groupBy(fn($rate) =>
                    optional($rate->option)->point
                )->map->count();
                $othersTotalRatings = $othersPointCounts->sum();

                $surveyOthersTotalRatings += $othersTotalRatings;
                $surveyOthersTotalPoints += $othersPoints;

                // Store per survey & type info
                $groupSurveyTypePoints[$survey->id][$typeName] = [
                    'self' => [
                        'total_points'  => $selfPoints,
                        'point_counts'  => $selfPointCounts,
                        'total_ratings' => $selfTotalRatings,
                    ],
                    'others' => [
                        'total_points'  => $othersPoints,
                        'point_counts'  => $othersPointCounts,
                        'total_ratings' => $othersTotalRatings,
                    ],
                ];

                // --- NEW: accumulate combined totals per question type ---
                if (!isset($combinedTotals[$typeName])) {
                    $combinedTotals[$typeName] = [
                        'self' => [
                            'total_points' => 0,
                            'total_ratings' => 0,
                        ],
                        'others' => [
                            'total_points' => 0,
                            'total_ratings' => 0,
                        ],
                    ];
                }

                $combinedTotals[$typeName]['self']['total_points'] += $selfPoints;
                $combinedTotals[$typeName]['self']['total_ratings'] += $selfTotalRatings;

                $combinedTotals[$typeName]['others']['total_points'] += $othersPoints;
                $combinedTotals[$typeName]['others']['total_ratings'] += $othersTotalRatings;
            }

            // Totals per survey
            $groupSurveyTypePoints[$survey->id]['totals'] = [
                'self' => [
                    'total_ratings' => $surveySelfTotalRatings,
                    'total_points'  => $surveySelfTotalPoints,
                ],
                'others' => [
                    'total_ratings' => $surveyOthersTotalRatings,
                    'total_points'  => $surveyOthersTotalPoints,
                ],
            ];

            $grandSelfTotalRatings += $surveySelfTotalRatings;
            $grandOthersTotalRatings += $surveyOthersTotalRatings;
            $grandSelfTotalPoints += $surveySelfTotalPoints;
            $grandOthersTotalPoints += $surveyOthersTotalPoints;
        }

        // Grand totals for all surveys
        $groupSurveyTypePoints['all_surveys_totals'] = [
            'self' => [
                'total_ratings' => $grandSelfTotalRatings,
                'total_points'  => $grandSelfTotalPoints,
            ],
            'others' => [
                'total_ratings' => $grandOthersTotalRatings,
                'total_points'  => $grandOthersTotalPoints,
            ],
        ];

        // Add the combined totals per question type to the result (without modifying models)
        $groupSurveyTypetotalPoints['combined_totals_by_type'] = $combinedTotals;

        return $groupSurveyTypetotalPoints;
    }
}

if (!function_exists('calculateallSurveyTypetotalPoints')) {
    function calculateallSurveyTypetotalPoints(Group $group)
    {
        // 👇 Fetch lowercase group type names
        $groupTypeNames = $group->groupTypes->pluck('name')->map(fn($n) => strtolower($n))->toArray();

        // 👇 Decide which type names to include based on group type
        if (in_array('family', $groupTypeNames)) {
            $typeNames = ['INTROVERTS', 'EXTRAVERT', 'RELATIONSHIP', 'SELF-PERCEPTION'];
        } elseif (in_array('friend', $groupTypeNames)) {
            $typeNames = ['SOCIAL', 'ACADEMIC'];
        } else {
            $typeNames = []; // fallback if needed
        }

        // 👇 Map type names to type IDs
        $typeMap = \App\Models\Type::whereIn('name', $typeNames)->pluck('id', 'name');

        $groupSurveyTypePoints = [];

        $grandSelfTotalRatings = 0;
        $grandOthersTotalRatings = 0;
        $grandSelfTotalPoints = 0;
        $grandOthersTotalPoints = 0;

        // Initialize combined totals per type
        $combinedTotals = [];

        foreach ($group->defaultSurveys() as $survey) {
            $surveySelfTotalRatings = 0;
            $surveyOthersTotalRatings = 0;
            $surveySelfTotalPoints = 0;
            $surveyOthersTotalPoints = 0;

            foreach ($typeMap as $typeName => $typeId) {
                // Self rating
                $selfRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                    ->where('survey_id', $survey->id)
                    ->where('users_id', auth()->id())
                    ->where('evaluatee_id', auth()->id())
                    ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                    ->with('option', 'question')
                    ->get();

                $selfPoints = $selfRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                $selfPointCounts = $selfRates->groupBy(fn($rate) =>
                    optional($rate->option)->point
                )->map->count();
                $selfTotalRatings = $selfPointCounts->sum();

                $surveySelfTotalRatings += $selfTotalRatings;
                $surveySelfTotalPoints += $selfPoints;

                // Others rating
                $othersRates = \App\Models\UsersSurveysRate::where('group_id', $group->id)
                    ->where('survey_id', $survey->id)
                    ->where('evaluatee_id', auth()->id())
                    ->where('users_id', '!=', auth()->id())
                    ->whereHas('question', fn($q) => $q->where('type_id', $typeId))
                    ->with('option', 'question')
                    ->get();

                $othersPoints = $othersRates->sum(fn($rate) => optional($rate->option)->point ?? 0);
                $othersPointCounts = $othersRates->groupBy(fn($rate) =>
                    optional($rate->option)->point
                )->map->count();
                $othersTotalRatings = $othersPointCounts->sum();

                $surveyOthersTotalRatings += $othersTotalRatings;
                $surveyOthersTotalPoints += $othersPoints;

                // Store per survey & type info
                $groupSurveyTypePoints[$survey->id][$typeName] = [
                    'self' => [
                        'total_points'  => $selfPoints,
                        'point_counts'  => $selfPointCounts,
                        'total_ratings' => $selfTotalRatings,
                    ],
                    'others' => [
                        'total_points'  => $othersPoints,
                        'point_counts'  => $othersPointCounts,
                        'total_ratings' => $othersTotalRatings,
                    ],
                ];

                // --- NEW: accumulate combined totals per question type ---
                if (!isset($combinedTotals[$typeName])) {
                    $combinedTotals[$typeName] = [
                        'self' => [
                            'total_points' => 0,
                            'total_ratings' => 0,
                        ],
                        'others' => [
                            'total_points' => 0,
                            'total_ratings' => 0,
                        ],
                    ];
                }

                $combinedTotals[$typeName]['self']['total_points'] += $selfPoints;
                $combinedTotals[$typeName]['self']['total_ratings'] += $selfTotalRatings;

                $combinedTotals[$typeName]['others']['total_points'] += $othersPoints;
                $combinedTotals[$typeName]['others']['total_ratings'] += $othersTotalRatings;
            }

            // Totals per survey
            $groupSurveyTypePoints[$survey->id]['totals'] = [
                'self' => [
                    'total_ratings' => $surveySelfTotalRatings,
                    'total_points'  => $surveySelfTotalPoints,
                ],
                'others' => [
                    'total_ratings' => $surveyOthersTotalRatings,
                    'total_points'  => $surveyOthersTotalPoints,
                ],
            ];

            $grandSelfTotalRatings += $surveySelfTotalRatings;
            $grandOthersTotalRatings += $surveyOthersTotalRatings;
            $grandSelfTotalPoints += $surveySelfTotalPoints;
            $grandOthersTotalPoints += $surveyOthersTotalPoints;
        }

        // Grand totals for all surveys
        $groupSurveyTypePoints['all_surveys_totals'] = [
            'self' => [
                'total_ratings' => $grandSelfTotalRatings,
                'total_points'  => $grandSelfTotalPoints,
            ],
            'others' => [
                'total_ratings' => $grandOthersTotalRatings,
                'total_points'  => $grandOthersTotalPoints,
            ],
        ];

        // Add the combined totals per question type to the result (without modifying models)
        $groupSurveyTypetotalPoints['combined_totals_by_type'] = $combinedTotals;

        return $groupSurveyTypetotalPoints;
    }
}


    if (!function_exists('allreport')) {
    function allreport()
    {
        $authUserId = auth()->user()->id;

        $individualSurveys = Survey::whereJsonContains('applies_to', 'Individual')->get();

        $surveyRates = Survey::whereHas('usersSurveysRates', function ($query) use ($authUserId) {
                $query->where('users_id', $authUserId)
                    ->where('evaluatee_id', $authUserId);
            })
            ->with([
                'usersSurveysRates' => function ($q) use ($authUserId) {
                    $q->where('users_id', $authUserId)
                      ->where('evaluatee_id', $authUserId)
                      ->with(['option', 'question']);
                }
            ])
            ->get();

        $surveyPoints = [];
        $typePoints = [
            'SELF' => [], 'COMPETENCE' => [], 'AUTONOMY' => [], 'RELATEDNESS' => [],
            'SOCIAL' => [], 'ACADEMIC' => [], 'INTROVERTS' => [], 'EXTRAVERT' => [],
            'RELATIONSHIP' => [], 'SELF-PERCEPTION' => [],
        ];
        $totalTypePoints = array_fill_keys(array_keys($typePoints), 0);
        $totalTypeRatings = array_fill_keys(array_keys($typePoints), 0);
        $totalTypeMaxPoints = array_fill_keys(array_keys($typePoints), 0);

        $typeMap = Type::whereIn('name', array_keys($typePoints))
            ->get()
            ->mapWithKeys(fn($type) => [strtoupper($type->name) => $type->id]);

        foreach ($surveyRates as $survey) {
            $total = 0;
            $typeTotals = array_fill_keys(array_keys($typePoints), 0);
            $isRosenberg = strtolower(trim($survey->title)) === 'rosenberg';
            $maxPoint = $isRosenberg ? 4 : 5;

            foreach ($survey->usersSurveysRates as $rate) {
                $point = optional($rate->option)->point;
                $questionTypeId = optional($rate->question)->type_id;

                if (!is_null($point)) {
                    $total += $point;

                    foreach ($typeMap as $typeName => $typeId) {
                        if ($questionTypeId == $typeId) {
                            $typeTotals[$typeName] += $point;
                            $totalTypePoints[$typeName] += $point;
                            $totalTypeRatings[$typeName] += 1;
                            $totalTypeMaxPoints[$typeName] += $maxPoint;
                        }
                    }
                }
            }

            $surveyPoints[$survey->id] = $total;

            foreach ($typeTotals as $typeName => $val) {
                $typePoints[$typeName][$survey->id] = $val;
            }
        }

        // Ensure user is assigned to all individual surveys
        foreach ($individualSurveys as $survey) {
            $isAssigned = DB::table('users_surveys')
                ->where('user_id', $authUserId)
                ->where('survey_id', $survey->id)
                ->exists();

            if (!$isAssigned) {
                DB::table('users_surveys')->insert([
                    'user_id' => $authUserId,
                    'survey_id' => $survey->id,
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Breakdown by survey
        $surveyBreakdown = [];
        foreach ($surveyRates as $survey) {
            $surveyId = $survey->id;
            $isRosenberg = strtolower(trim($survey->title)) === 'rosenberg';
            $maxPoint = $isRosenberg ? 4 : 5;

            $surveyBreakdown[$surveyId] = [
                'title' => $survey->title ?? 'Untitled',
                'type_points' => [],
                'type_ratings' => [],
                'type_percentages' => [],
                'max_point_per_question' => $maxPoint,
            ];

            foreach (array_keys($typePoints) as $typeName) {
                $points = $typePoints[$typeName][$survey->id] ?? 0;
                $ratings = 0;

                foreach ($survey->usersSurveysRates as $rate) {
                    if (optional($rate->question)->type_id == ($typeMap[$typeName] ?? null)) {
                        $ratings += 1;
                    }
                }

                $possible = $ratings * $maxPoint;
                $percentage = $possible > 0 ? round(($points / $possible) * 100, 2) : 0;

                $surveyBreakdown[$surveyId]['type_points'][$typeName] = $points;
                $surveyBreakdown[$surveyId]['type_ratings'][$typeName] = $ratings;
                $surveyBreakdown[$surveyId]['type_percentages'][$typeName] = $percentage;
            }
        }

        // Final overall percentages and statuses
        $overallPercentages = [];
        $overallStatuses = [];

        foreach ($totalTypePoints as $type => $totalPoints) {
            $maxPoints = $totalTypeMaxPoints[$type] ?? 0;
            $percentage = $maxPoints > 0 ? round(($totalPoints / $maxPoints) * 100, 0) : 0;
            $overallPercentages[$type] = $percentage;

            // Status logic
            if ($percentage >= 84) {
                $status = 'Perfect';
            } elseif ($percentage >= 60) {
                $status = 'Very Good';
            } elseif ($percentage >= 40) {
                $status = 'Good';
            } else {
                $status = 'Poor';
            }

            $overallStatuses[$type] = $status;
        }

        return [
            'total_points' => $totalTypePoints,
            'total_ratings' => $totalTypeRatings,
            'max_points' => $totalTypeMaxPoints,
            'overall_percentages' => $overallPercentages,
            'overall_statuses' => $overallStatuses,
            'by_survey' => $surveyBreakdown,
        ];
    }
}



// if (!function_exists('getAllGroupsCombinedTypeReportsCombined')) {
//     function getAllGroupsCombinedTypeReportsCombined()
//     {
//         $user = auth()->user();
//         if (!$user) return [];

//         $reports = [];
//         foreach ($user->groups as $group) {
//             $byType = calculateSurveyTypetotalPoints($group)['combined_totals_by_type'] ?? [];
//             $combinedByType = [];
//             foreach ($byType as $type => $totals) {
//                 $combinedByType[$type] = [
//                     'total_points' => ($totals['self']['total_points'] ?? 0) + ($totals['others']['total_points'] ?? 0),
//                     'total_ratings' => ($totals['self']['total_ratings'] ?? 0) + ($totals['others']['total_ratings'] ?? 0),
//                 ];
//             }
//             $reports[$group->id] = [
//                 'group_name' => $group->name,
//                 'by_type_combined' => $combinedByType,
//             ];
//         }
//         return $reports;
//     }
// }
if(!function_exists('allreport'))
{
    function allreport()
    {
        $authUserId = auth()->user()->id;

        // Retrieve all individual surveys (applies_to containing 'Individual')
        $individualSurveys = Survey::whereJsonContains('applies_to', 'Individual')->get();

        // Get user's answers (rates)
        $surveyRates = Survey::whereHas('usersSurveysRates', function ($query) use ($authUserId) {
                $query->where('users_id', $authUserId)
                    ->where('evaluatee_id', $authUserId);
            })
            ->with([
                'usersSurveysRates' => function ($q) use ($authUserId) {
                    $q->where('users_id', $authUserId)
                    ->where('evaluatee_id', $authUserId)
                    ->with('option'); // Load the selected option to get its points
                }
            ])
            ->get();

        // Sum points per survey and per type
        $surveyPoints = [];
        $typePoints = [
            'SELF' => [],
            'COMPETENCE' => [],
            'AUTONOMY' => [],
            'RELATEDNESS' => [],
        ];
        $totalTypePoints = [
            'SELF' => 0,
            'COMPETENCE' => 0,
            'AUTONOMY' => 0,
            'RELATEDNESS' => 0,
        ];
        // Get type IDs for each type name
        $typeMap = \App\Models\Type::whereIn('name', ['SELF', 'COMPETENCE', 'AUTONOMY', 'RELATEDNESS'])->pluck('id', 'name');
        foreach ($surveyRates as $survey) {
            $total = 0;
            $typeTotals = [
                'SELF' => 0,
                'COMPETENCE' => 0,
                'AUTONOMY' => 0,
                'RELATEDNESS' => 0,
            ];
            foreach ($survey->usersSurveysRates as $rate) {
                $point = optional($rate->option)->point ?? 0;
                $total += $point;
                $questionTypeId = optional($rate->question)->type_id;
                foreach ($typeMap as $typeName => $typeId) {
                    if ($questionTypeId == $typeId) {
                        $typeTotals[$typeName] += $point;
                        $totalTypePoints[$typeName] += $point; // <-- Add to total
                    }
                }
            }
            $surveyPoints[$survey->id] = $total;
            foreach ($typeTotals as $typeName => $val) {
                $typePoints[$typeName][$survey->id] = $val;
            }
        }

        // dd($surveyPoints);
        foreach ($individualSurveys as $survey) {
            // Check if the survey is already assigned to the authenticated user
            $isAssigned = DB::table('users_surveys')
                ->where('user_id', $authUserId)
                ->where('survey_id', $survey->id)
                ->exists();

            // If not assigned, assign it to the user
            if (!$isAssigned) {
                DB::table('users_surveys')->insert([
                    'user_id' => $authUserId,
                    'survey_id' => $survey->id,
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // dd($totalTypePoints);
             return $totalTypePoints;
    }
}
if (!function_exists('getAllGroupsCombinedTypeReportsCombined')) {
    function getAllGroupsCombinedTypeReportsCombined()
    {
        $user = auth()->user();
        if (!$user) return [];

        $reports = [];
        foreach ($user->groups as $group) {
            $byType = calculateSurveyTypetotalPoints($group)['combined_totals_by_type'] ?? [];
            $combinedByType = [];
            foreach ($byType as $type => $totals) {
                $combinedByType[$type] = [
                    'self_points_total'=>($totals['self']['total_points'] ?? 0),
                    'self_ratings_total'=>($totals['self']['total_ratings'] ?? 0),
                    'total_points' => ($totals['self']['total_points'] ?? 0) + ($totals['others']['total_points'] ?? 0),
                    'total_ratings' => ($totals['self']['total_ratings'] ?? 0) + ($totals['others']['total_ratings'] ?? 0),
                ];
            }
            $reports[$group->id] = [
                'group_name' => $group->name,
                'by_type_combined' => $combinedByType,
            ];
        }
        // dd($reports);
        return $reports;
    }
}

function getAllGroupsCombinedTypeReportsCombinedByGroupType()
{
    $user = auth()->user();
    if (!$user) return [];

    $result = [
        'family' => [],
        'friend' => [],
    ];

    foreach ($user->groups as $group) {
        $groupTypeNames = $group->groupTypes->pluck('name')->map(fn($n) => strtolower($n))->toArray();

        if (in_array('family', $groupTypeNames)) {
            $groupType = 'family';
            $allowedTypes = [
                'INTROVERTS',
                'Extravert',
                'Relationship',
                'Self-perception',
                'SOCIAL',
                'ACADEMIC'
            ];
        } elseif (in_array('friend', $groupTypeNames)) {
            $groupType = 'friend';
            $allowedTypes = [
                'SELF',
                'COMPETENCE',
                'AUTONOMY',
                'RELATEDNESS',
                'SELF-PERCEPTION',
                'RELATIONSHIP',
                'INTROVERTS',
                'EXTRAVERT',
                'SOCIAL',
                'ACADEMIC'
            ];
        } else {
            continue; // skip groups that are neither family nor friend
        }

        $byType = calculateSurveyTypetotalPoints($group)['combined_totals_by_type'] ?? [];

        foreach ($allowedTypes as $type) {
            $totals = $byType[$type] ?? [
                'self' => ['total_points' => 0, 'total_ratings' => 0],
                'others' => ['total_points' => 0, 'total_ratings' => 0]
            ];

            // Ensure the type entry exists
            if (!isset($result[$groupType][$type])) {
                $result[$groupType][$type] = [
                    'total_points' => 0,
                    'total_ratings' => 0,
                    'self_points_total' => 0,
                    'self_ratings_total' => 0,
                    'others_points_total' => 0,
                    'others_ratings_total' => 0,
                    'combined_points_total' => 0,
                    'combined_ratings_total' => 0,
                ];
            }

            // Add self totals
            $result[$groupType][$type]['self_points_total'] += $totals['self']['total_points'] ?? 0;
            $result[$groupType][$type]['self_ratings_total'] += $totals['self']['total_ratings'] ?? 0;

            // Add others totals
            $result[$groupType][$type]['others_points_total'] += $totals['others']['total_points'] ?? 0;
            $result[$groupType][$type]['others_ratings_total'] += $totals['others']['total_ratings'] ?? 0;

            // Optionally include combined total
            $result[$groupType][$type]['total_points'] += ($totals['self']['total_points'] ?? 0) + ($totals['others']['total_points'] ?? 0);
            $result[$groupType][$type]['total_ratings'] += ($totals['self']['total_ratings'] ?? 0) + ($totals['others']['total_ratings'] ?? 0);
        }
    }
        //  dd($result);
    return $result;
}

if (!function_exists('getAllSelfAwarenessQuestionsFlatByGroupType')) {
    function getAllSelfAwarenessQuestionsFlatByGroupType()
    {
        $user = auth()->user();
        // dd($user);
        if (!$user) return [];

        $selfAwarenessModelName = 'Self-awareness and motivation';
        $modelIds = \App\Models\SurveyModel::where('title', $selfAwarenessModelName)->pluck('id');
        if ($modelIds->isEmpty()) return [];

        $result = [
            'individual' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
            ],
            'family' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
                'others_total_points' => 0,
                'others_total_ratings' => 0,
            ],
            'friend' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
                'others_total_points' => 0,
                'others_total_ratings' => 0,
            ],
        ];

        // ✅ INDIVIDUAL: only self ratings
        $individualSurveys = \App\Models\Survey::whereJsonContains('applies_to', 'Individual')
            ->whereIn('model_id', $modelIds)
            ->get();

        // Debug: Log how many individual surveys we found
        \Log::info("Found " . $individualSurveys->count() . " individual surveys");

        foreach ($individualSurveys as $survey) {
            $questions = \App\Models\Question::where('survey_id', $survey->id)->get();

            // Debug: Log how many questions we found for this survey
            \Log::info("Survey {$survey->id} ({$survey->title}): Found " . $questions->count() . " questions");

            foreach ($questions as $question) {
                // Debug: Log question fields to see what's available
                \Log::info("Question {$question->id} fields: " . json_encode($question->toArray()));
                
                // Only get the latest self rating (where user rates themselves)
                $rate = \App\Models\UsersSurveysRate::where([
                        ['survey_id', $survey->id],
                        ['question_id', $question->id],
                        ['evaluatee_id', $user->id],
                        ['users_id', $user->id], // Self rating only
                    ])
                    ->with('option')
                    ->latest('id')
                    ->first();

                if ($rate && optional($rate->option)->point !== null) {
                    $point = $rate->option->point;
                    $result['individual']['self_total_points'] += $point;
                    $result['individual']['self_total_ratings'] += 1;

                    // Try different possible field names for question text
                    $questionText = $question->text ?? $question->title ?? $question->question ?? $question->name ?? $question->content ?? $question->description ?? 'Question ' . $question->id;

                    $result['individual']['questions'][] = [
                        'question_id' => $question->id,
                        'question_text' => $questionText,
                        'user_answer' => $rate->option,
                        'answer_point' => $point,
                        'group_name' => 'Individual',
                        'survey_title' => $survey->title ?? $survey->id,
                        'question_total_points' => $point,
                        'question_total_ratings' => 1,
                        'rating_type' => 'self',
                    ];

                    // Debug: Log each question we're adding
                    \Log::info("Added individual question: {$question->id} - {$questionText} (Point: {$point})");
                } else {
                    // Debug: Log questions that don't have ratings
                    $questionText = $question->text ?? $question->title ?? $question->question ?? $question->name ?? $question->content ?? $question->description ?? 'Question ' . $question->id;
                    \Log::info("No rating found for individual question: {$question->id} - {$questionText}");
                }
            }
        }

        // Debug: Log total individual questions before merging
        \Log::info("Total individual questions before merging: " . count($result['individual']['questions']));

        // ✅ GROUP SURVEYS: both self and others ratings
        foreach ($user->groups as $group) {
            $groupTypeNames = $group->groupTypes->pluck('name')->map(fn($n) => strtolower($n))->toArray();

            if (in_array('family', $groupTypeNames)) {
                $groupType = 'family';
            } elseif (in_array('friend', $groupTypeNames)) {
                $groupType = 'friend';
            } else {
                continue;
            }

            foreach ($group->defaultSurveys() as $survey) {
                // Only include surveys of the "Self-awareness and motivation" model
                if (!in_array($survey->model_id, $modelIds->toArray())) {
                    continue;
                }
                $questions = \App\Models\Question::where('survey_id', $survey->id)->get();

                foreach ($questions as $question) {
                    // Get the latest self rating (where user rates themselves)
                    $selfRate = \App\Models\UsersSurveysRate::where([
                            ['group_id', $group->id],
                            ['survey_id', $survey->id],
                            ['question_id', $question->id],
                            ['evaluatee_id', $user->id],
                            ['users_id', $user->id], // Self rating
                        ])
                        ->with('option')
                        ->latest('id')
                        ->first();

                    // Get others ratings (where others rate the user)
                    $othersRates = \App\Models\UsersSurveysRate::where([
                            ['group_id', $group->id],
                            ['survey_id', $survey->id],
                            ['question_id', $question->id],
                            ['evaluatee_id', $user->id],
                            ['users_id', '!=', $user->id], // Others rating
                        ])
                        ->with(['option', 'user'])
                        ->get();

                    // Calculate self points and ratings for this question
                    $selfQuestionPoints = 0;
                    $selfQuestionRatings = 0;
                    $selfUserAnswer = null;
                    $selfAnswerPoint = null;

                    if ($selfRate && optional($selfRate->option)->point !== null) {
                        $selfQuestionPoints = $selfRate->option->point;
                        $selfQuestionRatings = 1;
                        $selfUserAnswer = $selfRate->option;
                        $selfAnswerPoint = $selfQuestionPoints;
                        $result[$groupType]['self_total_points'] += $selfQuestionPoints;
                        $result[$groupType]['self_total_ratings'] += 1;
                    }

                    // Calculate others points and ratings for this question
                    $othersQuestionPoints = 0;
                    $othersQuestionRatings = 0;

                    foreach ($othersRates as $rate) {
                        $point = optional($rate->option)->point;
                        if (!is_null($point)) {
                            $othersQuestionPoints += $point;
                            $othersQuestionRatings += 1;
                            $result[$groupType]['others_total_points'] += $point;
                            $result[$groupType]['others_total_ratings'] += 1;
                        }
                    }

                    // Try different possible field names for question text
                    $questionText = $question->text ?? $question->title ?? $question->question ?? $question->name ?? $question->content ?? $question->description ?? 'Question ' . $question->id;

                    // Add question with both self and others data
                    $result[$groupType]['questions'][] = [
                        'question_id' => $question->id,
                        'question_text' => $questionText,
                        'group_name' => $group->name,
                        'survey_title' => $survey->title ?? $survey->id,
                        'self' => [
                            'user_answer' => $selfUserAnswer,
                            'answer_point' => $selfAnswerPoint,
                            'question_points' => $selfQuestionPoints,
                            'question_ratings' => $selfQuestionRatings,
                        ],
                        'others' => [
                            'question_points' => $othersQuestionPoints,
                            'question_ratings' => $othersQuestionRatings,
                        ],
                    ];
                }
            }
        }

        // Merge same questions across all groups to show total survey questions
        $mergedResult = [
            'individual' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
            ],
            'family' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
                'others_total_points' => 0,
                'others_total_ratings' => 0,
            ],
            'friend' => [
                'questions' => [],
                'self_total_points' => 0,
                'self_total_ratings' => 0,
                'others_total_points' => 0,
                'others_total_ratings' => 0,
            ],
        ];

        // Merge individual questions
        $individualQuestions = [];
        foreach ($result['individual']['questions'] as $question) {
            $questionId = $question['question_id'];
            if (!isset($individualQuestions[$questionId])) {
                $individualQuestions[$questionId] = [
                    'question_id' => $questionId,
                    'question_text' => $question['question_text'],
                    'self_total_points' => 0,
                    'self_total_ratings' => 0,
                    'groups' => [],
                    'surveys' => [],
                ];
            }
            $individualQuestions[$questionId]['self_total_points'] += $question['answer_point'];
            $individualQuestions[$questionId]['self_total_ratings'] += 1;
            $individualQuestions[$questionId]['groups'][] = $question['group_name'];
            $individualQuestions[$questionId]['surveys'][] = $question['survey_title'];
        }

        $mergedResult['individual']['questions'] = array_values($individualQuestions);
        $mergedResult['individual']['self_total_points'] = $result['individual']['self_total_points'];
        $mergedResult['individual']['self_total_ratings'] = $result['individual']['self_total_ratings'];

        // Merge family and friend questions
        foreach (['family', 'friend'] as $groupType) {
            $groupQuestions = [];
            $groupSelfTotalPoints = 0;
            $groupSelfTotalRatings = 0;
            $groupOthersTotalPoints = 0;
            $groupOthersTotalRatings = 0;
            
            foreach ($result[$groupType]['questions'] as $question) {
                $questionId = $question['question_id'];
                if (!isset($groupQuestions[$questionId])) {
                    $groupQuestions[$questionId] = [
                        'question_id' => $questionId,
                        'question_text' => $question['question_text'],
                        'self_total_points' => 0,
                        'self_total_ratings' => 0,
                        'others_total_points' => 0,
                        'others_total_ratings' => 0,
                        'groups' => [],
                        'surveys' => [],
                    ];
                }
                
                // Merge self data
                $groupQuestions[$questionId]['self_total_points'] += $question['self']['question_points'];
                $groupQuestions[$questionId]['self_total_ratings'] += $question['self']['question_ratings'];
                
                // Merge others data
                $groupQuestions[$questionId]['others_total_points'] += $question['others']['question_points'];
                $groupQuestions[$questionId]['others_total_ratings'] += $question['others']['question_ratings'];
                
                $groupQuestions[$questionId]['groups'][] = $question['group_name'];
                $groupQuestions[$questionId]['surveys'][] = $question['survey_title'];
            }

            // Calculate totals from merged questions
            foreach ($groupQuestions as $question) {
                $groupSelfTotalPoints += $question['self_total_points'];
                $groupSelfTotalRatings += $question['self_total_ratings'];
                $groupOthersTotalPoints += $question['others_total_points'];
                $groupOthersTotalRatings += $question['others_total_ratings'];
            }

            $mergedResult[$groupType]['questions'] = array_values($groupQuestions);
            $mergedResult[$groupType]['self_total_points'] = $groupSelfTotalPoints;
            $mergedResult[$groupType]['self_total_ratings'] = $groupSelfTotalRatings;
            $mergedResult[$groupType]['others_total_points'] = $groupOthersTotalPoints;
            $mergedResult[$groupType]['others_total_ratings'] = $groupOthersTotalRatings;
        }
// dd($mergedResult);
        return $mergedResult;
    }
}








