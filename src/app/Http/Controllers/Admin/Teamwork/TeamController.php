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

use App\Mail\Teams\OutBoundInvite;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

/**
 * Class TeamController.
 */
class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.teamwork.index')
            ->with('teams', auth()->user()->teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.teamwork.create', [
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'logo_file' => 'mimes:jpeg,jpg,png|max:5120',
            'team_manager' => 'string',
        ]);

        $logoFilePath = $request->file('logo_file')->store('images/teams/logos', 'public');

        $modelClass = config('teamwork.team_model');

        $teamManager = User::whereName($request->team_manager)->first();

        if (!$teamManager) {
            abort(RedirectResponse::HTTP_UNPROCESSABLE_ENTITY, 'Team Manager does not exist!');
        }

        $team = $modelClass::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->user()->getKey(),
            'logo' => $logoFilePath,
            'team_manager' => $request->team_manager,
        ]);

        $request->whenHas('team_invitees', function () use($request, $team) {
            foreach ($request->team_invitees as $id => $email) {
                $invitee = User::whereEmail($email)->first();
                if ($invitee) {
                    if( !Teamwork::hasPendingInvite( $invitee->email, $team) ) {
                        Teamwork::inviteToTeam( $invitee->email, $team, function( $invite ) {
                            $invite->invite_type = 'membership';
                            $invite->save();
                        });
                    }
                } else {
                    $teamInvite = new TeamInvite();

                    $teamInvite->user_id = $request->user()->id;
                    $teamInvite->team_id = $team->id;
                    $teamInvite->type = 'invite';
                    $teamInvite->accept_token = md5(uniqid(microtime()));
                    $teamInvite->deny_token = md5(uniqid(microtime()));
                    $teamInvite->email = $email[0];

                    $teamInvite->save();

                    Mail::to($team->owner->email)
                        ->send(new OutBoundInvite($team, $email[0]));
                }
            }
        });

        $request->user()->attachTeam($team);

        return redirect(route('teams.index'));
    }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return RedirectResponse
     */
    public function switchTeam($id): RedirectResponse
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        try {
            auth()->user()->switchTeam($team);
        } catch (UserNotInTeamException $e) {
            abort(403);
        }

        return redirect(route('teams.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id): View
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        return view('admin.teamwork.edit')->withTeam($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        $team->name = $request->name;
        $team->description = $request->description;
        $team->save();

        return redirect(route('teams.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        $userModel = config('teamwork.user_model');

        $userModel::where('current_team_id', $id)
                    ->update(['current_team_id' => null]);

        return redirect(route('teams.index'));
    }
}
