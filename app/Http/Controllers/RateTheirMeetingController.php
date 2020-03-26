<?php

namespace App\Http\Controllers;

use App\OtherMeeting;
use Illuminate\Http\Request;

class RateTheirMeetingController extends Controller
{
    public function index(){
        return view('ratetheirmeeting');
    }

    public function send(Request $request){
        $hostEmail = $request->hostemail;
        $attendeesEmail = $request->attendeesemail;
        $hostLinkedIn = $request->hostlinkedin;

        $om = new OtherMeeting();
        $om->host_email = $hostEmail;

    }
}
