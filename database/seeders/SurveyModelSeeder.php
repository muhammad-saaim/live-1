<?php

namespace Database\Seeders;

use App\Models\SurveyModel;
use Illuminate\Database\Seeder;

class SurveyModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surveys = [
            [
                'title' => 'Family',
                'description' => 'A survey designed to explore the dynamics, communication patterns, and relationships within a family unit.'
            ],
            [
                'title' => 'Self-awareness and motivation',
                'description' => 'This survey assesses an individual’s level of self-awareness and their intrinsic motivation towards personal and professional goals.'
            ],
            [
                'title' => 'Satisfaction',
                'description' => 'A survey aimed at measuring overall satisfaction across various aspects such as job, life, and personal achievements.'
            ],
            [
                'title' => 'Basic Psychological Needs',
                'description' => 'This survey evaluates how well an individual’s basic psychological needs are being met, including autonomy, competence, and relatedness.'
            ],
            [
                'title' => 'Vocational',
                'description' => 'A survey focused on understanding an individual’s career interests, job satisfaction, and future vocational goals.'
            ],
            [
                'title' => 'Sociometry',
                'description' => 'This survey analyzes social relationships within a group, identifying connections, preferences, and social dynamics.'
            ],
            [
                'title' => 'Rosenberg',
                'description' => 'A survey based on the Rosenberg Self-Esteem Scale, which measures an individual’s self-worth and self-respect.'
            ],
            [
                'title' => 'Self-awareness and motivation',
                'description' => 'A repeat survey assessing self-awareness and motivation, designed to compare progress or changes over time.'
            ],
        ];

        foreach ($surveys as $survey) {
            SurveyModel::create([
                'title' => $survey['title'],
                'description' => $survey['description'],
                'is_active' => true,
            ]);
        }
    }
}
