<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RateEventNewHost extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Rate this meeting';
    public $token_host;

    public function __construct($token_host)
    {
        $this->token_host = $token_host;
    }

    public function build()
    {
        return $this->view('emails.host-event1');
    }
}
