<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Mencetak 50 data dummy menggunakan Factory
        User::factory()->count(50)->create(); 
    }
}