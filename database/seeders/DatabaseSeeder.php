<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creating 3 users with different roles
        User::create([
            'user_name' => 'AdminUser',
            'email' => 'admin@gemail.com',
            'password' => Hash::make('password123'),
            'user_type' => 'admin',
            'avatar' => null,
        ]);

        User::create([
            'user_name' => 'AccountantUser',
            'email' => 'accountant@gemail.com',
            'password' => Hash::make('password123'),
            'user_type' => 'accountant',
            'avatar' => null,
        ]);

        User::create([
            'user_name' => 'HRUser',
            'email' => 'hr@gemail.com',
            'password' => Hash::make('password123'),
            'user_type' => 'hr',
            'avatar' => null,
        ]);
    }
}
