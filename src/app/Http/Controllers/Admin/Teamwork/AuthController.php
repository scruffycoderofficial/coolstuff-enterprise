<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin\Teamwork;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Mpociot\Teamwork\Facades\Teamwork;

/**
 * Class AuthController.
 */
class AuthController extends Controller
{
    /**
     * Accept the given invite.
     *
     * @param $token
     * @return RedirectResponse
     */
    public function acceptInvite($token)
    {
        $invite = Teamwork::getInviteFromAcceptToken($token);

        if (! $invite) {
            abort(404);
        }

        if (auth()->check()) {
            Teamwork::acceptInvite($invite);

            return redirect()->route('teams.index');
        } else {
            session(['invite_token' => $token]);

            return redirect()->to('login');
        }
    }
}
