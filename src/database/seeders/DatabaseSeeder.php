<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

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
            'name' => 'John Doe',
            'password' => 'johnd',
            'email' => 'johnd@doeorg.com',
        ]);

        User::factory()->create([
            'name' => 'Mary Doe',
            'password' => 'maryd',
            'email' => 'maryd@doeorg.com',
        ]);

        User::factory()->create([
            'name' => 'Christian Doe',
            'password' => 'chrisd',
            'email' => 'chrisd@doeorg.com',
        ]);
    }
}
