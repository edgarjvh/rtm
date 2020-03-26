<?php

namespace App\Http\Controllers;

use App\InvitationToken;
use App\Mail\JoinTeam;
use App\Organization;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

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

    public function calendarAuthorization()
    {
        $org = Organization::where('id', $this->user->organization_id)->first();
        $organization = $org->name;

        return view('home')->with(['userLogged' => $this->user, 'organization' => $organization]);
    }

    public function showInvitations()
    {
        $org = Organization::where('id', $this->user->organization_id)->first();
        $organization = $org->name;

        $this->user->has_invited = 1;
        $this->user->save();

        return view('invite-your-team')->with(['organization' => $organization]);
    }

    public function sendInvitations(Request $request)
    {
        $sent = 0;
        $notSent = 0;

        $partners = explode(',', $request->partners);

        if (count($partners) > 0){
            for ($i = 0; $i < count($partners); $i++) {
                $partner = $partners[$i];

                if ($this->isEmailValid($partner)) {

                    $isRegistered = User::where('email', $partner)->first();

                    if ($isRegistered){
                        $notSent++;
                    }else{
                        $token = Str::random(100);

                        $invitation_token =new InvitationToken();
                        $invitation_token->user_id = $this->user->id;
                        $invitation_token->partner = $partner;
                        $invitation_token->token = $token;
                        $invitation_token->save();

                        Mail::to($partner)->send(new JoinTeam($this->user->name, $token));
                        $sent++;
                    }
                }else{
                    $notSent++;
                }
            }
        }

        if ($sent > 0) {
            User::where('email', Auth::user()->email)->update([
                'has_invited' => 1
            ]);
        }

        return view('invite-your-team')->with(['sent' => $sent, 'notSent' => $notSent]);
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

            if (count($rates) > 0) {
                $global_avg = array_sum($rates) / count($rates);
            } else {
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

    function isEmailValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
