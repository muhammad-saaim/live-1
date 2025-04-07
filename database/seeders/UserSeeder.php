<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'              => 'Max',
            'email'             => 'makhsatsaniyaz@gmail.com',
            'username'          => 'developer',
            'password'          => Hash::make('W7VDCG*2Cp9L'),
            'email_verified_at' => now(),
            'remember_token'    => Hash::make(Str::random(10)),
            'country'           => 'Mongolia',
        ]);
        $user1 = User::create([
            'name'              => 'Hasan',
            'email'             => 'orehberlik@gmail.com',
            'username'          => 'admin',
            'password'          => Hash::make('W7VDCG*2Cp9L'),
            'email_verified_at' => now(),
            'remember_token'    => Hash::make(Str::random(10)),
            'country'           => 'USA',
        ]);

        $user->assignRole('Admin');
        $user1->assignRole('Admin');
    }
}
