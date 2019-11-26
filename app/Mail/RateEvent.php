<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RateEvent extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Rate this meeting';
    public $rating_key;
    public $event_id;

    public function __construct($rating_key, $event_id)
    {
        $this->rating_key = $rating_key;
        $this->event_id = $event_id;
    }

    public function build()
    {
        return $this->view('emails.rate-event');
    }
}
