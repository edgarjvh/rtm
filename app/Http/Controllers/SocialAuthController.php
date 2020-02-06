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
        $googleScopes = [
            'https://www.googleapis.com/auth/calendar.readonly',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email'
        ];

        $linkedinScopes = [
            'r_basicprofile',
            'r_emailaddress'
        ];

        switch ($provider) {
            case 'linkedin':
                return Socialite::driver($provider)
                    ->scopes($linkedinScopes)
                    ->redirect();
            default:
                return Socialite::driver($provider)
                    ->scopes($googleScopes)
                    ->with(["access_type" => "offline", "prompt" => "consent select_account"])
                    ->redirect();
        }
    }

    public function handleProviderCallback($provider)
    {
        switch ($provider) {
            case 'linkedin':
                try {
                    $userSocial = Socialite::driver($provider)->user();

                    $id = $userSocial->id;
                    $name = $userSocial->name;
                    $email = $userSocial->email;
                    $avatar = $userSocial->avatar;
                    $access_token = $userSocial->token;

                    $user = User::where(['email' => $email])->first();

                    if ($user) {
                        User::where(['email' => $email])->update(array(
                            'linkedin_account' => $email,
                            'linkedin_id' => $id,
                            'linkedin_access_token' => $access_token,
                            'linkedin_avatar' => $avatar
                        ));

                        Auth::login($user);
                        return redirect('/home');

                    } else {
                        return view('auth.register', [
                            'name' => $name,
                            'email' => $email
                        ]);
                    }
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    dd($e);
                }

                break;

            default:
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

                break;
        }
    }
}
