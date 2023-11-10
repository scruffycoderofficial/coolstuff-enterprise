<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

final class UsersController extends Controller
{
    /**
     * Display all users.
     *
     * @return View
     */
    public function index()
    {
        $users = User::orderBy('id')->latest()->paginate(6);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form for creating user.
     *
     * @return View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     *
     * @param User $user
     * @param StoreUserRequest $request
     *
     * @return Response
     */
    public function store(User $user, StoreUserRequest $request)
    {
        $password = Str::password();

        $user->create(array_merge($request->validated(), [
            'password' => Hash::make($password),
        ]));

        //Email the password to the User

        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    /**
     * Show user data.
     *
     * @param User $user
     *
     * @return View
     */
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Edit user data.
     *
     * @param User $user
     *
     * @return View
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get(),
        ]);
    }

    /**
     * Update user data.
     *
     * @param User $user
     * @param UpdateUserRequest $request
     *
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update($request->validated());

        $user->syncRoles($request->get('role'));

        return redirect()->route('users.index')
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Delete user data.
     *
     * @param User $user
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }
}
