<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;




class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'Sulymanbarakat11@gmail.com',
            'password' => bcrypt('@Bo12la34ji56'), 
            'is_admin' => true,
        ]);
    }
}


