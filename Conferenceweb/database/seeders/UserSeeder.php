<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin
    User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'), // Password is 'password'
        'userrole' => 'admin',
    ]);

    // Create Organizer
    User::create([
        'name' => 'Organizer',
        'email' => 'organizer@example.com',
        'password' => Hash::make('password'), // Password is 'password'
        'userrole' => 'organizer',
    ]);

    // Create User
    User::create([
        'name' => 'User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'), // Password is 'password'
        'userrole' => 'user',
    ]);
    }
}
