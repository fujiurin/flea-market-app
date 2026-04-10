<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
