<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

use App\Models\User;
use App\Models\Team;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

// Dashboard > Users
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('admin.users.index'));
});

// Dashboard > Users
Breadcrumbs::for('users_show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users');
    $trail->push('Show ' . $user->name . ' Details', route('admin.users.index'));
});

// Dashboard > Teams
Breadcrumbs::for('teams', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Teams', route('teams.index'));
});

// Dashboard > Teams > Team Members
Breadcrumbs::for('team_members', function (BreadcrumbTrail $trail) {
    $trail->parent('teams');
    $trail->push('Team Members', route('teams.index'));
});

// Dashboard > Teams > Add new
Breadcrumbs::for('team_create', function (BreadcrumbTrail $trail) {
    $trail->parent('teams');
    $trail->push('Add new', route('teams.create'));
});

// Dashboard > Teams > Add new
Breadcrumbs::for('team_edit', function (BreadcrumbTrail $trail, Team  $team) {
    $trail->parent('teams');
    $trail->push('Edit ' . $team->name, route('teams.edit', $team));
});
