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

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::findOrFail(24);

        if ($user) {
            $team = Team::create([
                'name' => 'CoolStuff Porters',
                'description' => 'An internal team that serves as a Demo purpose, to also showcase some of the underlying framework gooddies under the hood.',
                'team_manager' => 'Luyanda Siko'
            ]);

            $user->attachTeam($team);
        }
    }
}
