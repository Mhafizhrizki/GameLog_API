<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'user1@user.com'],
            [
                'name' => 'User 1',
                'password' => bcrypt('user1234'),
                'role' => 'user'
            ]
        );
    }
}
