<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Relation;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $relations = [
            'Father', 'Mother', 'Son', 'Daughter', 'Brother', 'Sister',
            'Uncle', 'Aunt', 'Cousin', 'Grandfather', 'Grandmother',
            'Nephew', 'Niece', 'Spouse','Grandson', 'Granddaughter'
        ];

        foreach ($relations as $name) {
            Relation::create([
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
