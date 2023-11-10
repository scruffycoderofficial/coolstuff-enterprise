<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

namespace App\Listeners\Teamwork;

use Teamwork;

class JoinTeamListener
{
    /**
     * See if the session contains an invite token on login and try to accept it.
     * @param mixed $event
     */
    public function handle($event)
    {
        if (session('invite_token')) {
            if ($invite = Teamwork::getInviteFromAcceptToken(session('invite_token'))) {
                Teamwork::acceptInvite($invite);
            }
            session()->forget('invite_token');
        }
    }
}