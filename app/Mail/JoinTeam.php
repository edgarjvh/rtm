<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinTeam extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Join my team at "Rate this meeting"';
    public $team_leader_name;
    public $token;

    public function __construct($team_leader_name, $token)
    {
        $this->team_leader_name = $team_leader_name;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.join-team');
    }
}
