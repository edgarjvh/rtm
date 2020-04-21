<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyToken;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyController extends Controller
{
    public function __construct(){

    }

    public function verifyEmailFirst($email){
        $user = User::where('email', strtolower($email))->whereNotNull('email_verified_at')->first();

        if ($user){
            return redirect(route('login'));
        }

        return view('auth.verify', ['email' => strtolower($email), 'resend' => false]);
    }

    public function resendEmail($email){
        $token = Str::random(40);

        User::where('email', strtolower($email))->update(array(
            'verify_token' => $token
        ));

        $curUser = User::where('email', strtolower($email))->first();

        Mail::to(strtolower($email))->send(
            new VerifyToken($curUser)
        );

        return view('auth.verify')->with(['email' => strtolower($email), 'resend' => true]);
    }

    public function verifying($email, $token){
        $user = User::where(['email' => strtolower($email), 'verify_token' => $token])->first();

        if ($user){
            User::where(['email' => strtolower($email), 'verify_token' => $token])->update(array(
                'email_verified_at' => date('Y-m-d H:i:s', time()),
                'verify_token' => null
            ));

            if (Auth::loginUsingId($user->id)){
                return redirect('/home');
            }else{
                return redirect('/login');
            }

        }else{
            return view('error')->with('message', 'Invalid verification token');
        }
    }
}
