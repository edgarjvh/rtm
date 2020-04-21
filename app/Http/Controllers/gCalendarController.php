<?php

namespace App\Http\Controllers;

use App\Event;
use App\Exclusion;
use App\RatingKey;
use App\Recipient;
use App\Setting;
use DateTime;
use DateTimeZone;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_People;
use GuzzleHttp\Exception\ClientException;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class gCalendarController extends Controller
{
    protected $client;

    function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('consent select_account');
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        $client->addScope(Google_Service_People::USERINFO_PROFILE);
        $client->addScope(Google_Service_People::USERINFO_EMAIL);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);

        $this->client = $client;
    }

    public function index()
    {
        session_start();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $optParams = array(
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c', strtotime('first day of this month'))
            );

            $results = $service->events->listEvents($calendarId, $optParams);
            return $results->getItems();
        } else {
            return redirect('/googleAuth');
        }
    }

    public function getByRefreshToken()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->google_refresh_token != null) {
                $userSettings = Setting::where('user_id', $user->id)->first();

                try {
                    $client = new Google_Client();
                    $client->setAuthConfig('client_secret.json');
                    $client->setAccessType('offline');
                    $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
                    $client->addScope(Google_Service_People::USERINFO_PROFILE);
                    $client->addScope(Google_Service_People::USERINFO_EMAIL);

                    $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
                    $client->setHttpClient($guzzleClient);

                    $client->refreshToken(addslashes($user->google_refresh_token));
                    $client->getAccessToken();

                    $service = new Google_Service_Calendar($client);

                    $calendarId = 'primary';

                    $optParams = array(
                        'orderBy' => 'startTime',
                        'singleEvents' => true,
                        'timeMin' => date('c', strtotime('-1 days')),
                        'timeMax' => date('c')
                    );

                    $results = $service->events->listEvents($calendarId, $optParams);

                    if (count($results->getItems()) > 0) {

                        for ($i = 0; $i < count($results->getItems()); $i++) {
                            // validate events as meeting
                            $item = $results->getItems()[$i];
                            $now = time();

                            // check if event is not set for all day
                            if ($item['start']['dateTime'] && $item['end']['dateTime']) {
                                $dtStart = date('U', strtotime($item['start']['dateTime']));
                                $dtEnd = date('U', strtotime($item['end']['dateTime']));

                                // is meeting over in less than 1 day
                                if ((ceil(($now - $dtEnd) / 60) >= 0) && ((ceil($now - $dtEnd) / 60) < 1440)) {

                                    // is meeting duration is greater than 20 minutes but less than 1 day
                                    if ((ceil(($dtEnd - $dtStart) / 60) > 20) && (ceil(($dtEnd - $dtStart) / 60) < 1440)) {

                                        if ($item['attendees']) {
                                            $acceptedCount = 0;

                                            foreach ($item['attendees'] as $att) {
                                                if ($att['responseStatus'] == "accepted") $acceptedCount++;
                                            }

                                            // if three or more attendees have accepted
                                            if ($acceptedCount >= 3) {
                                                $start_date = new DateTime($item['start']['dateTime']);
                                                $start_date->setTimezone(new DateTimeZone("UTC"));
                                                $end_date = new DateTime($item['end']['dateTime']);
                                                $end_date->setTimezone(new DateTimeZone("UTC"));

                                                for ($x = 0; $x < count($item['attendees']); $x++) {
                                                    $attendee = $item['attendees'][$x];

                                                    $isExcluded = Exclusion::where([
                                                        'user_email' => strtolower($user->email),
                                                        'email' => $attendee['email']
                                                    ])->first();

                                                    if ($isExcluded) {
                                                        echo 'Google Event Id: ' . $item['id'] . ' Excluded email (' . strtolower($attendee['email']) . ')';
                                                        echo '<br>';
                                                    } else {
                                                        if ($attendee['responseStatus'] == "accepted") {

                                                            // IF USER IS THE HOST
                                                            if (strtolower($user->email) == strtolower($item['organizer']['email'])) {

                                                                // IF HAS SENDING RATING EMAILS ENABLED
                                                                if ($userSettings->sending_rating_emails == 1) {
                                                                    // if attendee is not the organizer, send email for rating
                                                                    if (strtolower($item['organizer']['email']) != strtolower($attendee['email'])) {

                                                                        // CHECKING IF EMAIL IS NOT ALREADY SENT
                                                                        $isAlreadySent = Recipient::where(['event_id' => $item['id'], 'recipient' => $attendee['email']])->first();

                                                                        if (!$isAlreadySent) {
                                                                            $rating_key = Str::random(100);

                                                                            $key = new RatingKey();
                                                                            $key->rating_key = $rating_key;
                                                                            $key->save();

                                                                            app()->call('\App\Http\Controllers\MessagesController@sendEmail',
                                                                                [
                                                                                    strtolower($attendee['email']),
                                                                                    $rating_key,
                                                                                    $item['id'],
                                                                                    $item['summary'],
                                                                                    $item['organizer']['email'],
                                                                                    $start_date->format('Y-m-d H:i:s'),
                                                                                    $end_date->format('Y-m-d H:i:s')
                                                                                ]);

                                                                            $recipient = new Recipient();
                                                                            $recipient->organizer = strtolower($item['organizer']['email']);
                                                                            $recipient->recipient = strtolower($attendee['email']);
                                                                            $recipient->start_date = $start_date->format('Y-m-d H:i:s');
                                                                            $recipient->end_date = $end_date->format('Y-m-d H:i:s');
                                                                            $recipient->attendees = count($item['attendees']);
                                                                            $recipient->event_id = $item['id'];
                                                                            $recipient->provider = 'google';
                                                                            $recipient->title = $item['summary'];
                                                                            $recipient->description = $item['description'];
                                                                            $recipient->save();

                                                                            Event::updateOrCreate(
                                                                                [
                                                                                    'event_id' => $item['id']
                                                                                ],
                                                                                [
                                                                                    'organizer' => strtolower($item['organizer']['email']),
                                                                                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                                                                                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                                                                                    'attendees' => count($item['attendees']),
                                                                                    'event_id' => $item['id'],
                                                                                    'provider' => 'google',
                                                                                    'title' => $item['summary'],
                                                                                    'description' => $item['description']
                                                                                ]);

                                                                        } else {
                                                                            echo 'Google Event Id: ' . $item['id'] . ' already emailed to ' . strtolower($attendee['email']);
                                                                            echo '<br>';
                                                                        }
                                                                    }
                                                                }
                                                            } else {

                                                                // IF HAS SENDING RATING EMAILS NOT HOSTED ENABLED
                                                                if ($userSettings->sending_rating_emails_not_hosted == 1) {
                                                                    // CHECKING IF ORGANIZER IS ALREADY REGISTERED WITH US
                                                                    $isHostRegistered = User::where('email', strtolower($item['organizer']['email']))->first();

                                                                    if (!$isHostRegistered) {
                                                                        // CHECKING IF EMAIL IS NOT ALREADY SENT
                                                                        $isAlreadySent = Recipient::where(['event_id' => $item['id'], 'recipient' => $attendee['email']])->first();

                                                                        if (!$isAlreadySent) {
                                                                            // CHECK IF ORGANIZER OR ANOTHER ATTENDEE
                                                                            if (strtolower($item['organizer']['email']) == strtolower($attendee['email'])) {

                                                                                $rating_key = Str::random(100);

                                                                                $key = new RatingKey();
                                                                                $key->rating_key = $rating_key;
                                                                                $key->save();

                                                                                app()->call('\App\Http\Controllers\MessagesController@sendEmailToHost',
                                                                                    [
                                                                                        strtolower($attendee['email']),
                                                                                        $rating_key,
                                                                                        $item['id'],
                                                                                        $item['summary'],
                                                                                        $item['organizer']['email'],
                                                                                        $start_date->format('Y-m-d H:i:s'),
                                                                                        $end_date->format('Y-m-d H:i:s')
                                                                                    ]);

                                                                                $recipient = new Recipient();
                                                                                $recipient->organizer = $item['organizer']['email'];
                                                                                $recipient->recipient = strtolower($attendee['email']);
                                                                                $recipient->start_date = $start_date->format('Y-m-d H:i:s');
                                                                                $recipient->end_date = $end_date->format('Y-m-d H:i:s');
                                                                                $recipient->attendees = count($item['attendees']);
                                                                                $recipient->event_id = $item['id'];
                                                                                $recipient->provider = 'google';
                                                                                $recipient->title = $item['summary'];
                                                                                $recipient->description = $item['description'];
                                                                                $recipient->save();

                                                                            } else {

                                                                                $rating_key = Str::random(100);

                                                                                $key = new RatingKey();
                                                                                $key->rating_key = $rating_key;
                                                                                $key->save();

                                                                                app()->call('\App\Http\Controllers\MessagesController@sendEmailToAttendee',
                                                                                    [
                                                                                        strtolower($attendee['email']),
                                                                                        $rating_key,
                                                                                        $item['id'],
                                                                                        $item['summary'],
                                                                                        $item['organizer']['email'],
                                                                                        $start_date->format('Y-m-d H:i:s'),
                                                                                        $end_date->format('Y-m-d H:i:s')
                                                                                    ]);

                                                                                $recipient = new Recipient();
                                                                                $recipient->organizer = $item['organizer']['email'];
                                                                                $recipient->recipient = strtolower($attendee['email']);
                                                                                $recipient->start_date = $start_date->format('Y-m-d H:i:s');
                                                                                $recipient->end_date = $end_date->format('Y-m-d H:i:s');
                                                                                $recipient->attendees = count($item['attendees']);
                                                                                $recipient->event_id = $item['id'];
                                                                                $recipient->provider = 'google';
                                                                                $recipient->title = $item['summary'];
                                                                                $recipient->description = $item['description'];
                                                                                $recipient->save();

                                                                            }
                                                                        } else {
                                                                            echo 'Google Event Id: ' . $item['id'] . ' already emailed to ' . strtolower($attendee['email']);
                                                                            echo '<br>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo 'Google Event Id: ' . $item['id'] . ' - ' . $acceptedCount . ' attendees accepted';
                                                echo '<br>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (\Google_Exception $e) {
//                    User::where('email', $user->email)->update(array(
//                        'google_account' => null,
//                        'google_access_token' => null,
//                        'google_refresh_token' => null,
//                        'google_expiry_token' => null,
//                        'google_avatar' => null,
//                        'google_id' => null
//                    ));
                }

            }
        }
    }

    public function handleRateTheirMeeting()
    {
        $users = User::all();

        for ($u = 0; $u < count($users); $u++) {
            $user = $users[$u];

            if (strtolower($user->email) == strtolower(env('RATE_THEIR_MEETING_ACCOUNT'))) {

                if ($user && $user->google_refresh_token != null) {
                    try {
                        $client = new Google_Client();
                        $client->setAuthConfig('client_secret.json');
                        $client->setAccessType('offline');
                        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
                        $client->addScope(Google_Service_People::USERINFO_PROFILE);
                        $client->addScope(Google_Service_People::USERINFO_EMAIL);

                        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
                        $client->setHttpClient($guzzleClient);

                        $client->refreshToken(addslashes($user->google_refresh_token));
                        $client->getAccessToken();

                        $service = new Google_Service_Calendar($client);

                        $calendarId = 'primary';

                        $optParams = array(
                            'orderBy' => 'startTime',
                            'singleEvents' => true,
                            'timeMin' => date('c', strtotime('-1 days')),
                            'timeMax' => date('c')
                        );

                        $results = $service->events->listEvents($calendarId, $optParams);

                        if (count($results->getItems()) > 0) {
                            for ($i = 0; $i < count($results->getItems()); $i++) {
                                // validate events as meeting
                                $item = $results->getItems()[$i];
                                $now = time();

                                // HOST SHOULDN'T EXIST
                                $hostExist = User::where('email', strtolower($item['organizer']['email']))->first();

                                if (!$hostExist) {
                                    // check if event is not set for all day
                                    if ($item['start']['dateTime'] && $item['end']['dateTime']) {
                                        $dtStart = date('U', strtotime($item['start']['dateTime']));
                                        $dtEnd = date('U', strtotime($item['end']['dateTime']));

                                        // is meeting over in less than 1 day
                                        if ((ceil(($now - $dtEnd) / 60) >= 0) && ((ceil($now - $dtEnd) / 60) < 1440)) {
                                            // is meeting duration is greater than 20 minutes but less than 1 day
                                            if ((ceil(($dtEnd - $dtStart) / 60) > 20) && (ceil(($dtEnd - $dtStart) / 60) < 1440)) {
                                                if ($item['attendees']) {
                                                    $acceptedCount = 0;

                                                    foreach ($item['attendees'] as $att) {
                                                        if ($att['responseStatus'] == "accepted") $acceptedCount++;
                                                    }

                                                    // if three or more attendees have accepted
                                                    if ($acceptedCount >= 3) {
                                                        $start_date = new DateTime($item['start']['dateTime']);
                                                        $start_date->setTimezone(new DateTimeZone("UTC"));
                                                        $end_date = new DateTime($item['end']['dateTime']);
                                                        $end_date->setTimezone(new DateTimeZone("UTC"));

                                                        for ($x = 0; $x < count($item['attendees']); $x++) {
                                                            $attendee = $item['attendees'][$x];

                                                            $isExcluded = Exclusion::where([
                                                                'user_email' => strtolower($user->email),
                                                                'email' => $attendee['email']
                                                            ])->first();

                                                            if ($isExcluded) {
                                                                echo 'Google Event Id: ' . $item['id'] . ' Excluded email (' . strtolower($attendee['email']) . ')';
                                                                echo '<br>';
                                                            } else {
                                                                if ($attendee['responseStatus'] == "accepted" &&
                                                                    (strtolower($attendee['email']) != strtolower($item['organizer']['email'])) &&
                                                                    (strtolower($attendee['email']) != strtolower(env('RATE_THEIR_MEETING_ACCOUNT')))) {

                                                                    // CHECKING IF EMAIL IS NOT ALREADY SENT
                                                                    $isAlreadySent = Recipient::where(['event_id' => $item['id'], 'recipient' => $attendee['email']])->first();

                                                                    if (!$isAlreadySent) {
                                                                        $rating_key = Str::random(100);

                                                                        $key = new RatingKey();
                                                                        $key->rating_key = $rating_key;
                                                                        $key->save();

                                                                        app()->call('\App\Http\Controllers\MessagesController@sendEmail',
                                                                            [
                                                                                strtolower($attendee['email']),
                                                                                $rating_key,
                                                                                $item['id'],
                                                                                $item['summary'],
                                                                                $item['organizer']['email'],
                                                                                $start_date->format('Y-m-d H:i:s'),
                                                                                $end_date->format('Y-m-d H:i:s')
                                                                            ]);

                                                                        $recipient = new Recipient();
                                                                        $recipient->organizer = strtolower($item['organizer']['email']);
                                                                        $recipient->recipient = strtolower($attendee['email']);
                                                                        $recipient->start_date = $start_date->format('Y-m-d H:i:s');
                                                                        $recipient->end_date = $end_date->format('Y-m-d H:i:s');
                                                                        $recipient->attendees = count($item['attendees']);
                                                                        $recipient->event_id = $item['id'];
                                                                        $recipient->provider = 'google';
                                                                        $recipient->title = $item['summary'];
                                                                        $recipient->description = $item['description'];
                                                                        $recipient->save();

                                                                        Event::firstOrCreate(
                                                                            [
                                                                                'event_id' => $item['id']
                                                                            ],
                                                                            [
                                                                                'organizer' => strtolower($item['organizer']['email']),
                                                                                'start_date' => $start_date->format('Y-m-d H:i:s'),
                                                                                'end_date' => $end_date->format('Y-m-d H:i:s'),
                                                                                'attendees' => count($item['attendees']),
                                                                                'provider' => 'google',
                                                                                'title' => $item['summary'],
                                                                                'description' => $item['description']
                                                                            ]);

                                                                    } else {
                                                                        echo 'Google Event Id: ' . $item['id'] . ' already emailed to ' . strtolower($attendee['email']);
                                                                        echo '<br>';
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        // AFTER SENDING EMAILS TO ALL ATTENDEES, WE PROCEED TO SEND THE NOTIFICATION TO THE HOST

                                                        $token_host = Str::random(100);

                                                        $newHost = new User();
                                                        $newHost->name = '';
                                                        $newHost->email = strtolower($item['organizer']['email']);
                                                        $newHost->email_verified_at = now();
                                                        $newHost->password = Hash::make(Str::random(10));
                                                        $newHost->organization_id = 0;
                                                        $newHost->organization_owner = 1;
                                                        $newHost->token_host = $token_host;
                                                        $newHost->save();

                                                        app()->call('\App\Http\Controllers\MessagesController@sendEmailToNewHost',
                                                            [
                                                                strtolower($item['organizer']['email']),
                                                                $token_host
                                                            ]);
                                                    } else {
                                                        echo 'Google Event Id: ' . $item['id'] . ' - ' . $acceptedCount . ' attendees accepted';
                                                        echo '<br>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            echo 'no results to show';
                            echo '<br>';
                        }

                    } catch (\Google_Exception $e) {

                    }
                } else {
                    echo 'User not exist';
                    echo '<br>';
                }

                break;
            }else{
                echo 'User not exist';
                echo '<br>';
            }
        }
    }

    function str_crypt($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'rtmkey';
        $secret_iv = 'rtmiv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function googleAuth()
    {
        $email = Auth::user()->email;

        $rurl = action('gCalendarController@googleAuth');
        $this->client->setRedirectUri($rurl);

        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);

            $service = new Google_Service_People($this->client);

            $person = $service->people->get('people/me', [
                'requestMask.includeField' => [
                    'person.names',
                    'person.emailAddresses'
                ],
            ]);

            $userInfoEmail = ($person['emailAddresses'][0]['value']);

            $accountExist = User::where('google_account', $userInfoEmail)->first();

            //dd($accountExist);

            if ($accountExist) {
                return view('error')->with('message', 'Selected account has already been authorized.');
            }

            $user = User::where(['email' => $email])->first();

            $token = $this->client->getAccessToken();

            // print("<pre>".print_r($token['access_token'],true)."</pre>");
            if ($user) {
                User::where(['email' => $email])->update(array(
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'],
                    'google_expiry_token' => time() + $token['expires_in'],
                    'google_account' => $userInfoEmail
                ));
            }

            return redirect('/home');
        }
    }
}
