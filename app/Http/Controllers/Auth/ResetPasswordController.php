<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PassReset;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function requestNewPassword()
    {
        return view('auth.passwords.email', ['resend' => false, 'expired' => false]);
    }

    public function passwordSent(Request $request)
    {
        $token = Str::random(40);
        $expiry = time() + 3600;

        User::where('email', $request->email)->update(array(
            'password_token' => $token,
            'password_expiry_token' => $expiry
        ));

        $curUser = User::where('email', $request->email)->first();

        Mail::to($request->email)->send(
            new PassReset($curUser)
        );

        return view('auth.passwords.email', ['resend' => true, 'expired' => false]);
    }

    public function resetting($email, $token)
    {
        $user = User::where(['email' => $email, 'password_token' => $token])->first();

        if ($user) {
            if ($user->password_expiry_token < time()) {
                return view('auth.passwords.email', ['resend' => false, 'expired' => true]);
            } else {
                return view('auth.passwords.reset', ['email' => $email, 'token' => $token]);
            }

        } else {
            return view('error')->with('message', 'Invalid verification token');
        }
    }

    public function updating(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;
        $passwordConfirmation = $request->password_confirmation;

        $user = User::where(['email' => $email, 'password_token' => $token])->first();

        if ($user){
            if (strlen($password) < 8 || ($password !== $passwordConfirmation)){
                return view('auth.passwords.reset')->with([
                    'email' => $email,
                    'token' => $token,
                    'password_error_message' => 'Passwords must be at least eight characters and match the confirmation.'
                ]);
            }else{
                User::where(['email' => $email, 'password_token' => $token])->update(array(
                    'password' => Hash::make($password),
                    'password_token' => null,
                    'password_expiry_token' => null
                ));

                return redirect(route('login'));
            }
        }else{
            return redirect(route('resetting',[
                'email' => $email,
                'token' => $token,
                'email_error_message' => 'We can\'t find a user with that e-mail address.'
            ]));
        }
    }
}
