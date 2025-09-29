<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        if (config('APP_ENV') == 'local'){
            User::factory()->count(50)->create();
            $this->call([
                CountriesSeeder::class,
                RolesPermissionsSeeder::class,
                UserSeeder::class,
                GroupSeeder::class,
                TypeSeeder::class,
                GroupTypesTableSeeder::class,
                SurveyModelSeeder::class,
                RelationSeeder::class,
                ServicesSeeder::class,
            ]);
        }else{
            $this->call([
                CountriesSeeder::class,
                RolesPermissionsSeeder::class,
                UserSeeder::class,
                TypeSeeder::class,
                GroupTypesTableSeeder::class,
                SurveyModelSeeder::class,
                RelationSeeder::class,
                ServicesSeeder::class,
            ]);
        }
    }
}
