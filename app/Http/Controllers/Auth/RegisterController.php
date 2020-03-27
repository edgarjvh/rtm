<?php

namespace App\Http\Controllers\Auth;

use App\InvitationToken;
use App\Mail\VerifyToken;
use App\Organization;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
//        $_SESSION['register_type'] = 'owner';
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
//            'organization' => ['required', 'string', 'max:191'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
//        dd($_SESSION);

        $org_id = 0;

        if ($data['tokenteam'] != '0'){
            $invitationToken = InvitationToken::where('token', $data['tokenteam'])->first();

            if ($invitationToken){
                $_user = User::where('id', $invitationToken->user_id)->first();
                if ($_user){
                    $org_id = $_user->organization_id;
                }
            }
        }

        if (isset($_SESSION['registration_type'])){
            if ($_SESSION['registration_type'] == 'email'){
                $user = User::create([
                    'name' => $data['name'],
                    'email' => strtolower($data['email']),
                    'organization_id' => $org_id,
                    'organization_owner' => $data['owner'],
                    'password' => Hash::make($data['password']),
                    'verify_token' => Str::random(40),
                ]);

                $curUser = User::findOrFail($user->id);
                $this->sendEmail($curUser);
                return $user;
            }else{

                $user = User::create([
                    'name' => $data['name'],
                    'email' => strtolower($data['email']),
                    'organization_id' => $org_id,
                    'organization_owner' => $data['owner'],
                    'password' => Hash::make($data['password']),
                    'verify_token' => Str::random(40),
                    'email_verified_at' => now(),

                    'google_access_token' => $_SESSION['google_access_token'],
                    'google_refresh_token' => $_SESSION['google_refresh_token'],
                    'google_account' => $_SESSION['google_account'],
                    'google_id' => $_SESSION['google_id'],
                    'google_avatar' => $_SESSION['google_avatar'],
                    'google_expiry_token' => $_SESSION['google_expiry_token'],

                    'linkedin_access_token' => $_SESSION['linkedin_access_token'],
                    'linkedin_refresh_token' => $_SESSION['linkedin_refresh_token'],
                    'linkedin_account' => $_SESSION['linkedin_account'],
                    'linkedin_id' => $_SESSION['linkedin_id'],
                    'linkedin_avatar' => $_SESSION['linkedin_avatar'],
                    'linkedin_expiry_token' => $_SESSION['linkedin_expiry_token'],

                ]);

                $_SESSION['login_type'] = $_SESSION['registration_type'];
                Auth::login($user);
                return $user;
            }
        }else{
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'organization_id' => $org_id,
                'organization_owner' => $data['owner'],
                'password' => Hash::make($data['password']),
                'verify_token' => Str::random(40),
            ]);

            $curUser = User::findOrFail($user->id);
            $this->sendEmail($curUser);
            return $user;
        }
    }

    public function verifyEmailFirst($email){
        $user = User::where('email', strtolower($email))->whereNotNull('email_verified_at')->first();

        if ($user){
            return redirect(route('login'));
        }

        return view('auth.verify', ['email' => strtolower($email), 'resend' => false]);
    }

    public function sendEmail($curUser){
        Mail::to($curUser['email'])->send(
            new VerifyToken($curUser)
        );
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
                return redirect(route('/home'));
            }else{
                return redirect(route('/login'));
            }

        }else{
            return view('error')->with('message', 'Invalid verification token');
        }
    }


}
