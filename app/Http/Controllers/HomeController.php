<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }

        $this->middleware(['auth' => 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userEmail = Auth::user()->email;

        $user = User::where('email', $userEmail)->first();

        $organization = '';

        if ($user->organization_owner === 1) {
            $org = Organization::where('id', $user->organization_id)->first();
            $organization = $org->name;
        }

        return view('home')->with(array('userEmail' => $userEmail, 'userLogged' => $user, 'organization' => $organization));
    }
}
