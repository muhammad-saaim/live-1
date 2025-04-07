<?php

namespace Database\Seeders;

use App\Models\GroupType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GroupTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the group types to insert
        $now = Carbon::now();
        $groupTypes = [
            ['name' => 'Friend', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Family', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'School', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insert the group types into the table
        GroupType::insert($groupTypes);

        // Retrieve the 'School' group type to add child group types
        $school = GroupType::where('name', 'School')->first();

        if ($school) {
            // Define the child group types for 'School'
            $childGroupTypes = [
                ['name' => 'Primary', 'parent_id' => $school->id, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Middle', 'parent_id' => $school->id, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'High', 'parent_id' => $school->id, 'created_at' => $now, 'updated_at' => $now],
            ];

            // Insert the child group types into the table
            GroupType::insert($childGroupTypes);
        }
    }
}
