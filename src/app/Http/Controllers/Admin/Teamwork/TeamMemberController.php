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

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

/**
 * Class TeamMemberController.
 */
class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the members of the given team.
     *
     * @param  int $id
     * @return View
     */
    public function show($id): View
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        return view('admin.teamwork.members.list')->withTeam($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $team_id
     * @param int $user_id
     * @return RedirectResponse
     * @internal param int $id
     */
    public function destroy($team_id, $user_id): RedirectResponse
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);

        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($user_id);

        if ($user->getKey() === auth()->user()->getKey()) {
            abort(403);
        }

        $user->detachTeam($team);

        return redirect(route('teams.index'));
    }

    /**
     * @param Request $request
     * @param int $team_id
     * @return RedirectResponse
     */
    public function invite(Request $request, $team_id): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);

        if (! Teamwork::hasPendingInvite($request->email, $team)) {
            Teamwork::inviteToTeam($request->email, $team, function ($invite) {
                Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                    $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
                });
                // Send email to user
            });
        } else {
            return redirect()->back()->withErrors([
                'email' => 'The email address is already invited to the team.',
            ]);
        }

        return redirect(route('teams.members.show', $team->id));
    }

    /**
     * Resend an invitation mail.
     *
     * @param $invite_id
     * @return RedirectResponse
     */
    public function resendInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);

        Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
            $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
        });

        return redirect(route('teams.members.show', $invite->team));
    }

    /**
     * Revoke an invitation mail.
     *
     * @param $invite_id
     * @return RedirectResponse
     */
    public function revokeInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);

        $team = $invite->team;

        $invite->delete();

        return redirect(route('teams.members.show', $team));
    }

    /**
     * Revoke an invitation mail.
     *
     * @param $invite_id
     * @return RedirectResponse
     */
    public function ownershipInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);

        if ($invite->invite_type == 'ownership') {
            return redirect(route('teams.members.show', $invite->team));
        } else {
            throw new \Exception("Unknow invite type request.");
        }
    }

    /**
     * Revoke an ownership invitation mail.
     *
     * @param $invite_id
     * @return RedirectResponse
     */
    public function ownershipRevoke($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);

        if ($invite->invite_type === 'ownership') {
            abort(401, 'Invalid invite type requested.');
        }

        $team = $invite->team;

        $invite->delete();

        return redirect(route('teams.members.show', $team));
    }
}
