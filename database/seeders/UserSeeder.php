<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Location of User
        $country = Country::inRandomOrder()->first();
        $state   = $country->states()->inRandomOrder()->first();
        $city    = $state->cities()->inRandomOrder()->first();
        
        // Create an admin user
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Demi-God',
            'email' => 'admin@demigod.com',
            'phone_number' => '+911911911911',
            'email_verified_at' => now(),
            'password' => Hash::make('coffeemaker'),
            'country_id' => $country->id,
            'state_id' => $state?->id,
            'city_id' => $city?->id,
            'address' => '123 Admin Street',
            'credit' => 10000.00,
        ]);

        // Add to admins table
        DB::table('admins')->insert([
            'user_id' => $admin->id,
        ]);

        // // Create a test user
        // User::create([
        //     'first_name' => 'Test',
        //     'last_name' => 'User',
        //     'email' => 'test@example.com',
        //     'phone' => '+0987654321',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'address' => '456 Test Avenue',
        //     'credit' => 5000.00,
        // ]);

        // // Create 50 random users
        // User::factory(50)->create();
    }
}