<?php

namespace App\Http\Controllers;

use App\Event;
use App\Organization;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EventsController extends Controller
{
    protected $projects;

    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->middleware(['auth', 'verified']);

            if (!Auth::user()) {
                Redirect::to('login')->send();
            } else {
                if (!Auth::user()->email_verified_at){
                    Redirect::to('/email/verify')->send();
                }
                $this->user = Auth::user();
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = array();

        if ($this->user->organization_owner === 1) {
            $events = User::where('users.organization_id', $this->user->organization_id)
                ->whereNotNull('events.id')
                ->leftJoin('events', 'events.organizer', '=', 'users.email')
                ->select('events.*','users.name')
                ->paginate(6);
        } else {
            $events = Event::where('organizer', $this->user->email)
                ->leftJoin('users', 'events.organizer', '=', 'users.email')
                ->select('events.*','users.name')
                ->paginate(6);
        }

        foreach ($events as $event) {
            $rate = Rating::where('event_id', $event->event_id)->avg('rate');
            $event['rate'] = $rate;
        }

        $organization = '';

        if ($this->user->organization_owner === 1) {
            $org = Organization::where('id', $this->user->organization_id)->first();
            $organization = $org->name;
        }

        $timezone = 'America/Caracas';
//        $timezone = $this->get_local_time();

        return view('events.index')->with(['newEvents' => $events, 'organization' => $organization, 'tz' => $timezone]);
    }


    function get_local_time(){
        $url = 'http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR'];
        $tz = file_get_contents($url);
        $tz = json_decode($tz,true)['timezone'];
        return $tz;
    }

    public function return_ip(){
        return view('returnip');
    }
}
