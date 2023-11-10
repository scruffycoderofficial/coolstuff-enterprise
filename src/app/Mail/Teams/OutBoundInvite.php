<?php

namespace App\Mail\Teams;

use App\Models\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class OutBoundInvite
 *
 * @package App\Mail\Teams
 */
class OutBoundInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inviting Team
     *
     * @var $team
     */
    public $team;

    /**
     * Possible User email address
     *
     * @var $email
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param string $email
     */
    public function __construct(Team $team, $email)
    {
        $this->team = $team;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $owner = $this->team->owner;

        return $this->withSwiftMessage(function ($message) use($owner) {
            $message->getHeaders()->addTextHeader('Reply-To', $owner->email);
        })
            ->to($this->email)
            ->subject("CoolStuff Solution Portall: {$owner->name} has invited you to join {$this->team->name} Team")
            ->markdown('emails.teams.outbound-invite');
    }
}
