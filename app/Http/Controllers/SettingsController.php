<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function setSendingRatingEmails(Request $request){
        $status = $request->status;

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        if ($setting){
            $setting->sending_rating_emails = $status == 'true' ? 1 : 0;
            $setting->save();
        }else{
            Setting::create([
                'user_id' => Auth::user()->id,
                'sending_rating_emails' => $status == 'true' ? 1 : 0
            ]);
        }

        return response()->json(['result' => 'OK', 'data' => $status == 'true' ? 1 : 0]);
    }

    public function setSharingMeetingScore(Request $request){
        $status = $request->status;

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        if ($setting){
            $setting->sharing_meeting_score = $status == 'true' ? 1 : 0;
            $setting->save();
        }else{
            Setting::create([
                'user_id' => Auth::user()->id,
                'sharing_meeting_score' => $status == 'true' ? 1 : 0
            ]);
        }

        return response()->json(['result' => 'OK', 'data' => $status == 'true' ? 1 : 0]);
    }
}
