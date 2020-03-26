<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class SharingController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->middleware(['auth', 'verified']);

            if (!Auth::user()) {
                Redirect::to('login')->send();
            } else {
                if (!Auth::user()->email_verified_at) {
                    Redirect::to('/email/verify')->send();
                }
                $this->user = Auth::user();
            }
            return $next($request);
        });
    }

    public function shareOnLinkedIn(){

        $state = substr(str_shuffle("0123456789abcHGFRlki"), 0, 10);
        $scopes = 'r_emailaddress,r_liteprofile,w_member_social';
        $url = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=".env('LINKEDIN_CLIENT_ID')."&redirect_uri=".env('LINKEDIN_CALLBACK_URL')."&scope=".$scopes;

        header( "Location: $url" );
        die();

    }
}
