<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Administrator', 'email' => 'admin@minimarket.test', 'role' => 'admin'],
            ['name' => 'Kasir 1', 'email' => 'kasir@minimarket.test', 'role' => 'kasir'],
        ] as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'role' => $account['role'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
