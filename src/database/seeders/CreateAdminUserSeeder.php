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

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::findByName('admin');

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        // Users to get the Admin ROLE automatically, if database is filled with seeders
        $sholaWilliams = User::create([
            'name' => 'Shiela McLachlan',
            'password' => 'Shiela123',
            'email' => 'shiela.w@gmail.com',
        ]);

        $luyandaSiko  = User::create([
            'name' => 'Luyanda Siko',
            'password' => 'luyanda',
            'email' => 'sikoluyanda@gmail.com',
        ]);

        // Assign the role admin to given users
        $sholaWilliams->assignRole([$role->id]);
        $luyandaSiko->assignRole([$role->id]);
    }
}
