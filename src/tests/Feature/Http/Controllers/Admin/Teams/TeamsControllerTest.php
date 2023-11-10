<?php

namespace Tests\Feature\Http\Controllers\Admin\Teams;

use App\Testing\Concerns\WithTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;

/**
 * Class TeamsControllerTest
 *
 * @package Tests\Feature\Http\Controllers\Admin\Teams
 */
class TeamsControllerTest extends TestCase
{
    use WithTeam, WithFaker, RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupTeamActor();
    }

    public function test_it_can_show_message_when_there_are_no_teams_available()
    {
        $this->actingAs($this->actor)
            ->get(route('teams.index'))
            ->assertSee('There currently are no Teams available. To add a new Team click on the Create Team call to action on the right-hand corner.');

        $this->assertDatabaseCount('teams', 0);
    }

    public function test_it_can_can_show_teams_when_available()
    {
        $postData = [
            'name' => $this->teamName(),
            'description' => $this->teamDescription(),
            'owner_id' => $this->actor->id,
            'logo_file' => $this->teamLogiFile(),
            'team_manager' => $this->teamManager(),
        ];

        $this->actingAs($this->actor)
            ->post(route('teams.store'), $postData);

        $this->actingAs($this->actor)
            ->get(route('teams.index'))
            ->assertSee($postData['name']);

        $this->assertDatabaseCount('teams', 1);
    }

    public function test_it_can_see_team_create_form()
    {
        $this->actingAs($this->actor)
            ->get(route('teams.create'))
            ->assertSee('Your Team Name')
            ->assertSee('Description')
            ->assertSee('Upload Logo')
            ->assertSee('Team Manager');
    }

    public function test_it_can_create_team()
    {
        $this->actingAs($this->actor)
            ->post(route('teams.store'), [
                'name' => $this->teamName(),
                'description' => $this->teamDescription(),
                'owner_id' => $this->actor->id,
                'logo_file' => $this->teamLogiFile(),
                'team_manager' => $this->teamManager(),
            ]
        )->assertRedirect(route('teams.index'));

        $this->assertDatabaseCount('team_invites', 0);
    }

    public function test_it_can_create_team_and_invite_existing_users()
    {
        $teamInvitees = [];

        array_map(function(array $userDetails) use(&$teamInvitees){
            array_push($teamInvitees, [ $userDetails['id'] => $userDetails['email']]);
        }, User::factory(3)->create()->toArray());

        $this->actingAs($this->actor)
            ->post(route('teams.store'), [
                    'name' => $this->teamName(),
                    'description' => $this->teamDescription(),
                    'owner_id' => $this->actor->id,
                    'logo_file' => $this->teamLogiFile(),
                    'team_manager' => $this->teamManager(),
                    'team_invitees' => $teamInvitees,
                ]
            )->assertRedirect(route('teams.index'));

        $this->assertDatabaseCount('team_invites', 3);
    }

    public function test_it_can_create_team_and_invite_existing_and_none_existing_users()
    {
        $teamInvitees = $noneExistingUsers = [];

        array_map(function(array $userDetails) use(&$teamInvitees) {
            array_push($teamInvitees, [ $userDetails['id'] => $userDetails['email']]);
        }, User::factory(5)->create()->toArray());

        array_map(function(array $userDetails) use(&$noneExistingUsers) {
            array_push($noneExistingUsers, [ 0 => $userDetails['email']]);
        }, User::factory(2)->make()->toArray());

        $this->actingAs($this->actor)
            ->post(route('teams.store'), [
                    'name' => $this->teamName(),
                    'description' => $this->teamDescription(),
                    'owner_id' => $this->actor->id,
                    'logo_file' => $this->teamLogiFile(),
                    'team_manager' => $this->teamManager(),
                    'team_invitees' => array_merge($teamInvitees, $noneExistingUsers),
                ]
            )->assertRedirect(route('teams.index'));

        $this->assertDatabaseCount('team_invites', 7);
    }

    public function test_it_can_create_team_and_invite_none_existing_users()
    {
        $noneExistingUsers = [];

        array_map(function(array $userDetails) use(&$noneExistingUsers) {
            array_push($noneExistingUsers, [ 0 => $userDetails['email']]);
        }, User::factory(2)->make()->toArray());

        $this->actingAs($this->actor)
            ->post(route('teams.store'), [
                    'name' => $this->teamName(),
                    'description' => $this->teamDescription(),
                    'owner_id' => $this->actor->id,
                    'logo_file' => $this->teamLogiFile(),
                    'team_manager' => $this->teamManager(),
                    'team_invitees' => $noneExistingUsers,
                ]
            )->assertRedirect(route('teams.index'));

        $this->assertDatabaseCount('team_invites', 2);
    }

    public function test_it_throws_exception_if_given_team_manager_does_not_exist()
    {
        $this->actingAs($this->actor)
            ->post(route('teams.store'), [
                    'name' => $this->teamName(),
                    'description' => $this->teamDescription(),
                    'owner_id' => $this->actor->id,
                    'logo_file' => $this->teamLogiFile(),
                    'team_manager' => 'Unknown Citizen',
                    'team_invitees' => [[0 => 'test1@example2.co.za'], [0 => 'test2@example2.co.za']],
                ]
            )->assertStatus(422);

        $this->assertDatabaseCount('team_invites', 0);
    }
}
