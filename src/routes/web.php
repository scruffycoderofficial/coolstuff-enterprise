<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Auth::routes();

//Route::get('/resume', [App\Http\Controllers\PdfGeneratorController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::group(['prefix' => 'admin'], function () {
    /*
     * Dashboard
     */
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    /*
     * Users
     */
    Route::get('/users', [App\Http\Controllers\Admin\UsersController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/users/show/{id}', [App\Http\Controllers\Admin\UsersController::class, 'show'])
        ->name('admin.users.show');

    /*
     * Teams
     */
    Route::group(['prefix' => 'teams'], function () {
        Route::get('/', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'index'])
            ->name('teams.index');

        Route::get('create', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'create'])
            ->name('teams.create');

        Route::any( 'teams', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'store'])
            ->name('teams.store');

        Route::get('edit/{id}', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'edit'])
            ->name('teams.edit');

        Route::put('edit/{id}', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'update'])
            ->name('teams.update');

        Route::delete('destroy/{id}', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'destroy'])
            ->name('teams.destroy');

        Route::get('switch/{id}', [App\Http\Controllers\Admin\Teamwork\TeamController::class, 'switchTeam'])
            ->name('teams.switch');

        Route::get('members/{id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'show'])
            ->name('teams.members.show');

        Route::get('members/resend/{invite_id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'resendInvite'])
            ->name('teams.members.resend_invite');

        Route::get('members/revoke/{invite_id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'revokeInvite'])
            ->name('teams.members.revoke_invite');

        Route::get('members/ownership/{invite_id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'ownershipInvite'])
            ->name('teams.members.ownership_invite');

        Route::get('members/ownership/revoke/{invite_id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'ownershipRevoke'])
            ->name('teams.members.revoke_ownership_invite');

        Route::post('members/{id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'invite'])
            ->name('teams.members.invite');

        Route::delete('members/{id}/{user_id}', [App\Http\Controllers\Admin\Teamwork\TeamMemberController::class, 'destroy'])
            ->name('teams.members.destroy');

        Route::get('accept/{token}', [App\Http\Controllers\Admin\Teamwork\AuthController::class, 'acceptInvite'])
            ->name('teams.accept_invite');
    });
});

Route::group(['middleware' => ['web']], function () {
    //upload route
    Route::post('/import', ['as'=>'import', 'uses'=>'Controller@import']);
});
