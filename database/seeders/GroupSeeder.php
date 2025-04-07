<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample groups data
        $groups = [
            ['name' => 'Admin', 'description' => 'Group for admin users'],
            ['name' => 'Editors', 'description' => 'Group for content editors'],
            ['name' => 'Subscribers', 'description' => 'Group for subscribers'],
            ['name' => 'Guests', 'description' => 'Group for guest users'],
        ];

        // Create groups and attach users to them
        foreach ($groups as $groupData) {
            $group = Group::create($groupData);

            // Attach random users to the group with timestamps
            $users = User::inRandomOrder()->take(rand(1, 30))->pluck('id');

            $attachData = [];
            $timestamp = Carbon::now();

            foreach ($users as $userId) {
                $attachData[$userId] = [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            $group->users()->attach($attachData);
        }
    }
}
