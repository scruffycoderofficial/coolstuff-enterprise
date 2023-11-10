<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserVerify;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * @param $token
     * @return RedirectResponse
     */
    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (! is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (! $user->email_verified_at) {
                $verifyUser->user->email_verified_at = 1;
                $verifyUser->user->save();
                $message = 'Your e-mail is verified. You can now login.';
            } else {
                $message = 'Your e-mail is already verified. You can now login.';
            }
        }

        return redirect()->route('login')->with('message', $message);
    }
}
