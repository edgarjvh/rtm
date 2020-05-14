<?php

namespace App\Http\Controllers;

use App\Event;
use App\Exclusion;
use App\Organization;
use App\Rating;
use App\Setting;
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->middleware(function ($request, $next) {
            $this->middleware(['auth', 'verified']);

            if (!Auth::user()) {
                Redirect::to('login')->send();
            } else {
                if (!Auth::user()->email_verified_at) {
                    return view('auth.verify', ['email' => strtolower(Auth::user()->email)]);
                }

                $this->user = Auth::user();

//                dd(!isset($this->user->organization_owner));

                if (!isset($this->user->organization_owner)) {
                    if (!isset($_SESSION['organization_owner'])) {
                        Redirect::to('/registration')->send();
                    }
                }

                if ($this->user->organization_id == 0) {

                    Redirect::to('/organization-setup')->send();
                }

                if (!$this->user->google_access_token && !$this->user->outlook_access_token) {
                    Redirect::to('/calendar-authorization')->send();
                }

                if ($this->user->has_invited == 0) {
                    Redirect::to('/invite-your-team')->send();
                }
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
        $user = Auth::user();
        $exclusions = Exclusion::where('user_email', $user->email)->get();
        $settings = Setting::firstOrNew(['user_id' => $user->id], [
            'sending_rating_emails' => 1,
            'sharing_meeting_score' => 1
        ]);
        $settings->save();

        $events = array();

        if ($this->user->organization_owner === 1) {
            $events = DB::select(
                "select
                IFNULL(avg(r.rate),0) as score,
                e.*, u.name, count(r.event_id) as 'responses'
                from events as e
                left join users as u on e.organizer = u.email
                left join ratings as r on e.event_id = r.event_id
                where u.organization_id = ". $this->user->organization_id ." and e.event_id is not null
                GROUP BY 
                e.event_id, 
                e.id,
                e.organizer,
                e.start_date,
                e.end_date,
                e.attendees,
                e.provider,
                e.title,
                e.description,
                e.created_at,
                e.updated_at,
                u.name
                order by start_date desc"
            );
        } else {
            $events = DB::select(
                "select
                IFNULL(avg(r.rate),0) as score,
                e.*, u.name, count(r.event_id) as 'responses'
                from events as e
                left join users as u on e.organizer = u.email
                left join ratings as r on e.event_id = r.event_id
                where u.email = '" . strtolower(Auth::user()->email) . "'   
                GROUP BY 
                e.event_id, 
                e.id,
                e.organizer,
                e.start_date,
                e.end_date,
                e.attendees,
                e.provider,
                e.title,
                e.description,
                e.created_at,
                e.updated_at,
                u.name             
                order by start_date desc"
            );
        }

        $rates = [];

        foreach ($events as $event) {
            if ($event->organizer == $this->user->email){
                if ($event->score > 0) {
                    $rates[] = $event->score;
                }
            }
        }

        if (count($rates) > 0) {
            $global_avg = array_sum($rates) / count($rates);
        } else {
            $global_avg = 0;
        }

        $org = Organization::where('id', $this->user->organization_id)->first();
        $organization = $org->name;

        $timezone = 'America/Caracas';
//        $timezone = $this->get_local_time();

        return view('events.index')->with([
            'newEvents' => $events,
            'organization' => $organization,
            'tz' => $timezone,
            'global_avg' => $global_avg,
            'userLogged' => $user,
            'exclusions' => $exclusions,
            'settings' => $settings
        ]);
    }


    function get_local_time()
    {
        $url = 'http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR'];
        $tz = file_get_contents($url);
        $tz = json_decode($tz, true)['timezone'];
        return $tz;
    }

    public function return_ip()
    {
        return view('returnip');
    }
}
