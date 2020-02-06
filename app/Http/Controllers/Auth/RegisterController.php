<?php

namespace App\Http\Controllers\Auth;

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
        $_SESSION['register_type'] = 'owner';
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
        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'organization_id' => 0,
            'organization_owner' => $data['owner'],
            'password' => Hash::make($data['password']),
            'verify_token' => Str::random(40),
        ]);
        $curUser = User::findOrFail($user->id);
        $this->sendEmail($curUser);
        return $user;

//        $org = Organization::where('name', $data['organization'])->first();
//
//        if($org){
//            $user = User::create([
//                'name' => $data['name'],
//                'email' => strtolower($data['email']),
//                'organization_id' => $org->id,
//                'organization_owner' => 0,
//                'password' => Hash::make($data['password']),
//                'verify_token' => Str::random(40),
//            ]);
//            $curUser = User::findOrFail($user->id);
//            $this->sendEmail($curUser);
//            return $user;
//        }else{
//            $org = Organization::create(['name' => $data['organization']]);
//
//            $user = User::create([
//                'name' => $data['name'],
//                'email' => strtolower($data['email']),
//                'organization_id' => $org->id,
//                'organization_owner' => 1,
//                'password' => Hash::make($data['password']),
//                'verify_token' => Str::random(40),
//            ]);
//            $curUser = User::findOrFail($user->id);
//            $this->sendEmail($curUser);
//            return $user;
//        }
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

        return view('auth.verify', ['email' => strtolower($email), 'resend' => true]);
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
