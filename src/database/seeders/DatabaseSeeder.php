<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::factory(19)->create();

        User::factory()->create([
             'name' => 'Luyanda Siko',
             'password' => 'admin',
             'email' => 'sikoluyanda@gmail.com',
        ]);
    }
}
