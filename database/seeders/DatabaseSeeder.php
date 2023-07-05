<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Actividades; 
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'role' => 2
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('secret'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Fernando Alarcon',
            'email' => 'erickfernando_20@hotmail.com',
            'password' => Hash::make('12345678'),
            'role' => 1
        ]); 

        Actividades::factory(20)->create(); 
    }
}
