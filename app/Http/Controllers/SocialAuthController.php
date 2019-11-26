<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;

class SocialAuthController extends Controller
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)
            ->scopes([
                'https://www.googleapis.com/auth/calendar.readonly',
                'https://www.googleapis.com/auth/userinfo.profile',
                'https://www.googleapis.com/auth/userinfo.email'
            ])
            ->with(["access_type" => "offline", "prompt" => "consent select_account"])
            ->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $userSocial = Socialite::driver($provider)->user();

            $gName = $userSocial->user['name'];
            $gEmail = $userSocial->user['email'];

            $user = User::where(['email' => $gEmail])->first();

            if ($user) {
                switch ($provider) {
                    case 'google':
                        User::where(['email' => $gEmail])->update(array(
                            'google_expiry_token' => $userSocial->expiresIn,
                            'google_avatar' => $userSocial->avatar,
                            'google_id' => $userSocial->id
                        ));
//                        User::where(['email' => $gEmail])->update(array(
//                            'google_access_token' => $userSocial->token,
//                            'google_refresh_token' => $userSocial->refreshToken,
//                            'google_expiry_token' => $userSocial->expiresIn,
//                            'google_avatar' => $userSocial->avatar,
//                            'google_id' => $userSocial->id
//                        ));
//
                        break;
                }

                Auth::login($user);
                return redirect('/home');
            } else {
                return view('auth.register', [
                    'name' => $gName,
                    'email' => $gEmail
                ]);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            dd($e);
        }
    }
}
