<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Report Services
            [
                'name' => 'Parent Report',
                'category' => 'report',
                'description' => 'Comprehensive personality assessment report for parents',
                'price' => 50.00,
                'is_active' => true,
                'metadata' => json_encode([
                    'duration' => 'One-time',
                    'delivery_method' => 'Digital PDF'
                ])
            ],
            [
                'name' => 'Child Report',
                'category' => 'report',
                'description' => 'Personality assessment report for children',
                'price' => 10.00,
                'is_active' => true,
                'metadata' => json_encode([
                    'duration' => 'One-time',
                    'delivery_method' => 'Digital PDF',
                    'age_range' => '5-17 years'
                ])
            ],
            
            // Mentoring Services
            [
                'name' => 'Mentoring 6 months',
                'category' => 'mentoring',
                'description' => 'Six months of personalized mentoring sessions',
                'price' => 200.00,
                'is_active' => true,
                'metadata' => json_encode([
                    'duration' => '6 months',
                    'sessions_included' => 12,
                    'session_length' => '60 minutes'
                ])
            ],
            [
                'name' => 'Mentoring 3 months',
                'category' => 'mentoring',
                'description' => 'Three months of personalized mentoring sessions',
                'price' => 150.00,
                'is_active' => true,
                'metadata' => json_encode([
                    'duration' => '3 months',
                    'sessions_included' => 6,
                    'session_length' => '60 minutes'
                ])
            ],
            [
                'name' => 'Mentoring Session',
                'category' => 'mentoring',
                'description' => 'Single mentoring session',
                'price' => 50.00,
                'is_active' => true,
                'metadata' => json_encode([
                    'duration' => '1 hour',
                    'sessions_included' => 1,
                    'session_length' => '60 minutes'
                ])
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
