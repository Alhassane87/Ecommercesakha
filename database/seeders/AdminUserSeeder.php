<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SAKHA_ADMIN_EMAIL', 'admin@example.com');
        $password = env('SAKHA_ADMIN_PASSWORD', 'password');

        User::firstOrCreate(
            ['email' => $email],
            ['name' => 'Admin', 'password' => Hash::make($password), 'role' => 'admin']
        );
    }
}
