<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MeetingsController extends Controller
{

    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->middleware(['auth', 'verified']);

            if (!Auth::user()) {
                Redirect::to('login')->send();
            } else {
                if (!Auth::user()->email_verified_at) {
                    Redirect::to('/email/verify')->send();
                }
                $this->user = Auth::user();
            }
            return $next($request);
        });
    }

    public function getMeetings()
    {
        if ($this->user) {
            $events = array();

            if ($this->user->organization_owner === 1) {
                $events = DB::select(
                    "(select
                    e.*, u.name
                    from users as u
                    left join events as e on u.google_account = e.organizer
                    where u.organization_id = " . $this->user->organization_id . " and e.event_id is not null)
                    UNION
                    (select
                    e.*, u.name
                    from events as e
                    left join users as u on u.outlook_account = e.organizer
                    where u.organization_id = " . $this->user->organization_id . " and e.event_id is not null)
                    order by start_date desc");
            } else {
                $events = DB::select(
                    "(select
                    e.*, u.name, concat('unrated') as rate
                    from events as e
                    left join users as u on e.organizer = u.google_account
                    where u.email = '" . strtolower(Auth::user()->email) . "')
                    UNION
                    (select
                    e.*, u.name, concat('unrated') as rate
                    from events as e
                    left join users as u on e.organizer = u.outlook_account
                    where u.email = '" . strtolower(Auth::user()->email) . "')
                    order by start_date desc");
            }

            $rates = [];

            foreach ($events as $event) {
                $rate = Rating::where('event_id', $event->event_id)->avg('rate');
                $event->rate = $rate;

                if ($event->organizer == $this->user->email) {
                    if (is_numeric($rate)) {
                        $rates[] = $rate;
                    }
                }
            }

            if (count($rates) > 0){
                $global_avg = array_sum($rates) / count($rates);
            }else{
                $global_avg = 0;
            }


            $organization = '';

            if ($this->user->organization_owner === 1) {
                $org = Organization::where('id', $this->user->organization_id)->first();
                $organization = $org->name;
            }

            $timezone = 'America/Caracas';

            return response()->json(['result' => 'ok', 'meetings' => $events, 'organization' => $organization, 'tz' => $timezone, 'global_avg' => $global_avg]);
        } else {
            return response()->json(['result' => 'no user']);
        }
    }
}
