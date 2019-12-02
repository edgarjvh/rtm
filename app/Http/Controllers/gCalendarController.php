<?php

namespace App\Http\Controllers;

use App\Event;
use App\RatingKey;
use DateTime;
use DateTimeZone;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_People;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\User;
use Auth;
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
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->addScope(Google_Service_People::USERINFO_PROFILE);
        $client->addScope(Google_Service_People::USERINFO_EMAIL);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);

        $this->client = $client;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function getByRefreshToken()    {

        $users = User::all();

        foreach ($users as $user) {
            if ($user->google_refresh_token != null) {

                try {
                    $client = new Google_Client();
                    $client->setAuthConfig('client_secret.json');
                    $client->setAccessType('offline');
                    $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS_READONLY);
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

                                        // check if user is also organizer
                                        if ($user->email == $item['organizer']['email']) {

                                            if ($item['attendees']){
                                                $acceptedCount = 0;

                                                foreach ($item['attendees'] as $att){
                                                    if ($att['responseStatus'] == "accepted") $acceptedCount++;
                                                }

                                                // if three or more attendees have accepted
                                                if ($acceptedCount >= 3) {

                                                    // check if event has already been emailed
                                                    $eventExist = Event::where('event_id', $item['id'])->first();

                                                    if (!$eventExist) {
                                                        $start_date = new DateTime( $item['start']['dateTime']);
                                                        $start_date->setTimezone(new DateTimeZone("UTC"));
                                                        $end_date = new DateTime( $item['end']['dateTime']);
                                                        $end_date->setTimezone(new DateTimeZone("UTC"));

                                                        for ($x = 0; $x < count($item['attendees']); $x++) {
                                                            $attendee = $item['attendees'][$x];

                                                            // if attendee is not the organizer, sent email for rating
                                                            if ($item['organizer']['email'] != $attendee['email']) {
                                                                $rating_key = Str::random(100);

                                                                $key = new RatingKey();
                                                                $key->rating_key = $rating_key;
                                                                $key->save();

                                                                app()->call('\App\Http\Controllers\MessagesController@sendEmail',
                                                                    [
                                                                        $attendee['email'],
                                                                        $rating_key,
                                                                        $item['id'],
                                                                        $item['summary'],
                                                                        $item['organizer']['email'],
                                                                        $start_date->format('Y-m-d H:i:s'),
                                                                        $end_date->format('Y-m-d H:i:s')
                                                                    ]);
                                                            }
                                                        }

                                                        $event = new Event();
                                                        $event->organizer = $item['organizer']['email'];
                                                        $event->start_date = $start_date->format('Y-m-d H:i:s');
                                                        $event->end_date = $end_date->format('Y-m-d H:i:s');
                                                        $event->attendees = count($item['attendees']);
                                                        $event->event_id = $item['id'];
                                                        $event->provider = 'google';
                                                        $event->title = $item['summary'];
                                                        $event->description = $item['description'];
                                                        $event->save();
                                                    }else{
                                                        echo 'Google Event Id: ' . $item['id'] . ' already emailed';
                                                        echo '<br>';
                                                    }

                                                }else{
                                                    echo 'Google Event Id: ' . $item['id'] . ' - ' . $acceptedCount . ' attendees accepted';
                                                    echo '<br>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (ClientException $e) {
                    User::where('email', $user->email)->update(array(
                        'google_account' => null,
                        'google_access_token' => null,
                        'google_refresh_token' => null,
                        'google_expiry_token' => null,
                        'google_avatar' => null,
                        'google_id' => null
                    ));
                }
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
