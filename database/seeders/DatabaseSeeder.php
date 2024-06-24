<?php

namespace Database\Seeders;

use App\Models\Role;
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
        User::factory()->create([
            'name' => 'Sourav Das',
            'email' => 's@a',
            'password' => 'poi098',
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'o@o',
            'password' => 'poi098',
            'role_id' => 2
        ]);

        User::factory()->create([
            'name' => 'Mark Miller',
            'email' => 'a@a',
            'password' => 'poi098',
            'role_id' => 3
        ]);

        User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'h@r',
            'password' => 'poi098',
            'role_id' => 4
        ]);

        User::factory()->create([
            'name' => 'Sam Smith',
            'email' => 'e@e',
            'password' => 'poi098',
            'role_id' => 5
        ]);

        User::factory()->create([
            'name' => 'Alex Xender',
            'email' => 's@k',
            'password' => 'poi098',
            'role_id' => 6
        ]);

        User::factory()->create([
            'name' => 'Bob Miller',
            'email' => 'u@u',
            'password' => 'poi098',
            'role_id' => 7
        ]);

        // User::factory(10)->create();

        Role::create([
            'name' => 'Super Admin',
        ]);
        Role::create([
            'name' => 'Organizer',
        ]);
        Role::create([
            'name' => 'Accountant',
        ]);
        Role::create([
            'name' => 'Human Resource',
        ]);
        Role::create([
            'name' => 'Employee',
        ]);
        Role::create([
            'name' => 'Store Keeper',
        ]);
        Role::create([
            'name' => 'User',
        ]);
    }
}
