<?php

namespace App\Http\Controllers;

use App\Mail\RateEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    public function sendEmail($email, $rating_key, $event_id, $meeting_subject)
    {
        Mail::to($email)->send(new RateEvent($rating_key, $event_id, $meeting_subject));
        echo $email . ' - Message Sent <br>';
    }
}