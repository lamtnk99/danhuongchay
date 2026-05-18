<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@danhuongchay.vn'],
            [
                'name' => 'Admin Đàn Hương Chay',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
