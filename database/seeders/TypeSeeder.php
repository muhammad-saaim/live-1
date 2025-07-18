<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'ACADEMIC', 'description' => 'Academic type'],
            ['name' => 'SOCIAL', 'description' => 'Social type'],
            ['name' => 'COMPETENCE', 'description' => 'Competence type'],
            ['name' => 'AUTONOMY', 'description' => 'Autonomy type'],
            ['name' => 'RELATEDNESS', 'description' => 'Relatedness type'],
            ['name' => 'SELF', 'description' => 'Self type'],
            ['name' => 'OTHERS', 'description' => 'Others type'],
            ['name' => 'PARENTS', 'description' => 'Parents type'],
            ['name' => 'INTROVERTS', 'description' => 'Introverts type'],
            ['name' => 'EXTRAVERT', 'description' => 'Extravert type'],
            ['name' => 'RELATIONSHIP', 'description' => 'Relationship type'],
            ['name' => 'SELF-PERCEPTION', 'description' => 'Self-perception type'],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }
}
