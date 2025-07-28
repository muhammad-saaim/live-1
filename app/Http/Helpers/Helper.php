<?php

use App\Models\User;
use App\Models\Relation;
use App\Models\UserRelative;
use App\Models\Group;

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

