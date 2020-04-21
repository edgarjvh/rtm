<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{
    public $user;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (Auth::user()) {
            $this->user = Auth::user();
        }
    }

    public function showRegistration()
    {
        if ($this->user) {
            if ($this->user->organization_owner) {
                Redirect::to('/home')->send();
            }
        }

        return view('auth.register');
    }

    public function showJoin(Request $request)
    {
        if (!isset($request->clicked)) {
            Redirect::to('/registration')->send();
        }

        return view('auth.join')->with(['user' => $this->user]);
    }

    public function showCreate(Request $request)
    {
        if (!isset($request->clicked)) {
            Redirect::to('/registration')->send();
        }

        return view('auth.create')->with('user', $this->user);
    }

    public function deleteAccount(Request $request)
    {
        $_SESSION['delete-user-id'] = Auth::user()->id;


        Auth::logout();

        return response()->json(['logout' => 'success']);
    }
}
