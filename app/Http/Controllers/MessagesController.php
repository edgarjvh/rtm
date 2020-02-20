<?php

namespace App\Http\Controllers;

use App\Mail\RateEvent;
use App\Mail\RateEventAttendee;
use App\Mail\RateEventHost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    public function sendEmail($email, $rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date)
    {
        Mail::to($email)->send(new RateEvent($rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date));
        echo $email . ' - Message Sent <br>';
    }

    public function sendEmailToHost($email, $rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date)
    {
        Mail::to($email)->send(new RateEventHost($rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date));
        echo $email . ' - Message Sent <br>';
    }

    public function sendEmailToAttendee($email, $rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date)
    {
        Mail::to($email)->send(new RateEventAttendee($rating_key, $event_id, $meeting_subject, $organizer_email, $start_date, $end_date));
        echo $email . ' - Message Sent <br>';
    }
}