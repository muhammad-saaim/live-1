<?php

use App\Models\User;
use App\Models\Relation;
use App\Models\UserRelative;

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