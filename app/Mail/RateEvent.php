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
    public $meeting_subject;
    public $organizer_email;
    public $start_date;
    public $end_date;

    public function __construct($rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date)
    {
        $this->rating_key = $rating_key;
        $this->event_id = $event_id;
        $this->meeting_subject = $meeting_subject;
        $this->organizer_email = $organizer_email;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function build()
    {
        return $this->view('emails.rate-event');
    }
}
