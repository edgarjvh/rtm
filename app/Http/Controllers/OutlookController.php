<?php

namespace App\Http\Controllers;

use App\Event;
use App\RatingKey;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use Auth;

class OutlookController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function login()
    {
        if (!$this->user) {
            Redirect::to('login')->send();
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize the OAuth client
        $oauthClient = new GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUri' => env('OAUTH_REDIRECT_URI'),
            'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => '',
            'scopes' => env('OAUTH_SCOPES')
        ]);

        $url = $oauthClient->getAuthorizationUrl(); // this can be set based on whatever

        $_SESSION['outlook_oauth_state'] = $oauthClient->getState();

        header("Location: $url");
        exit();
    }

    public function outlookauth()
    {
        if (!$this->user) {
            Redirect::to('login')->send();
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Authorization code should be in the "code" query param
        if (isset($_GET['code'])) {
            // Check that state matches
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['outlook_oauth_state'])) {
                exit('State provided in redirect does not match expected value.');
            }

            // Clear saved state
            unset($_SESSION['outlook_oauth_state']);

            // Initialize the OAuth client
            $oauthClient = new GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUri' => env('OAUTH_REDIRECT_URI'),
                'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
                'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
                'urlResourceOwnerDetails' => '',
                'scopes' => env('OAUTH_SCOPES')
            ]);

            try {
                // Make the token request
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                $outlook_user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();

                $accountExist = User::where('outlook_account', $outlook_user->getUserPrincipalName())->first();

                if ($accountExist) {
                    return view('error')->with(['message' => 'Selected account has already been authorized.']);
                }

                $this->storeTokens(
                    $this->user->email,
                    $outlook_user->getUserPrincipalName(),
                    $accessToken->getToken(),
                    $accessToken->getRefreshToken(),
                    $accessToken->getExpires()
                );

                return redirect('/home');
            } catch (IdentityProviderException $e) {
                exit('ERROR getting tokens: ' . $e->getMessage());
            }
            exit();
        } else {
            Redirect::to('/outlook')->send();
        }
    }

    public function outlookCalendar()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $users = User::whereNotNull('outlook_access_token')->get();

        if (count($users) > 0) {
            foreach ($users as $ouser) {
                $now = time() + 300;
                $otoken = $ouser->outlook_access_token;
                try {
                    $oauthClient = new GenericProvider([
                        'clientId' => env('OAUTH_APP_ID'),
                        'clientSecret' => env('OAUTH_APP_PASSWORD'),
                        'redirectUri' => env('OAUTH_REDIRECT_URI'),
                        'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
                        'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
                        'urlResourceOwnerDetails' => '',
                        'scopes' => env('OAUTH_SCOPES')
                    ]);

                    try {
                        $newToken = $oauthClient->getAccessToken('refresh_token', [
                            'refresh_token' => $ouser->outlook_refresh_token
                        ]);

                        // Store the new values
                        $this->storeTokens(
                            $ouser->email,
                            $ouser->outlook_account,
                            $newToken->getToken(),
                            $newToken->getRefreshToken(),
                            $newToken->getExpires()
                        );

                        $otoken = $newToken->getToken();
                    } catch (IdentityProviderException $e) {
                        return '';
                    }

                    $graph = new Graph();
                    $graph->setAccessToken($otoken);
                    $eventsQueryParams = array(

                        "\$select" => "subject,start,end,attendees,isAllDay, IsCancelled, IsOrganizer, organizer, bodyPreview, CreatedDateTime, ResponseStatus",
                        "\$orderby" => "Start/DateTime",
                        "\$top" => 1000,
                        "\$filter" => "Start/DateTime ge '". date('c', strtotime('-1 days')) ."' and End/DateTime le '". date('c') ."'"
                    );

                    $getEventsUrl = '/me/events?' . http_build_query($eventsQueryParams);
                    $events = $graph->createRequest('GET', $getEventsUrl)
                        ->setReturnType(Model\Event::class)
                        ->execute();

                    $now = time();

                    foreach ($events as $event) {
                        if ($event->getIsOrganizer() && !$event->getIsAllDay() && !$event->getIsCancelled()) {

                            $organizer = $event->getOrganizer()->getEmailAddress()->getAddress();
                            $dtStart = date('U', strtotime($event->getStart()->getDateTime()));
                            $dtEnd = date('U', strtotime($event->getEnd()->getDateTime()));

                            // is meeting over in less than 1 day
                            if ((ceil(($now - $dtEnd) / 60) >= 0) && ((ceil($now - $dtEnd) / 60) < 1440)) {

                                // is meeting duration is greater than 20 minutes but less than 1 day
                                if ((ceil(($dtEnd - $dtStart) / 60) > 20) && (ceil(($dtEnd - $dtStart) / 60) < 1440)) {

                                    if (count($event->getAttendees()) >= 3) {

                                        $eventExist = Event::where('event_id', $event->getId())->first();

                                        // if event is not in database, will be saved
                                        if (!$eventExist) {
                                            $start_date = new DateTime($event->getStart()->getDateTime());
                                            $start_date->setTimezone(new DateTimeZone("UTC"));
                                            $end_date = new DateTime( $event->getEnd()->getDateTime());
                                            $end_date->setTimezone(new DateTimeZone("UTC"));

                                            foreach ($event->getAttendees() as $attendee) {
                                                if ($attendee['emailAddress']['address'] !== $organizer) {
                                                    $rating_key = Str::random(100);

                                                    $key = new RatingKey();
                                                    $key->rating_key = $rating_key;
                                                    $key->save();

                                                    app()->call('\App\Http\Controllers\MessagesController@sendEmail',
                                                        [
                                                            $attendee['emailAddress']['address'],
                                                            $rating_key,
                                                            $event->getId(),
                                                            $event->getSubject(),
                                                            $organizer,
                                                            $start_date->format('Y-m-d H:i:s'),
                                                            $end_date->format('Y-m-d H:i:s')
                                                        ]);
                                                }
                                            }


                                            $newEvent = new Event();
                                            $newEvent->organizer = $organizer;
                                            $newEvent->start_date = $start_date->format('Y-m-d H:i:s');
                                            $newEvent->end_date = $end_date->format('Y-m-d H:i:s');
                                            $newEvent->attendees = count($event->getAttendees());
                                            $newEvent->event_id = $event->getId();
                                            $newEvent->provider = 'outlook';
                                            $newEvent->title = $event->getSubject();
                                            $newEvent->description = $event->getBodyPreview();
                                            $newEvent->save();
                                        }else{
                                            echo 'Outlook Event Id: ' . $event->getId() . ' already emailed';
                                            echo '<br>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (IdentityProviderException $e) {
                    User::where('email', $ouser->email)->update(array(
                        'outlook_account' => null,
                        'outlook_access_token' => null,
                        'outlook_refresh_token' => null,
                        'outlook_expiry_token' => null,
                        'outlook_avatar' => null,
                        'outlook_id' => null
                    ));
                }
            }
        }
    }

    public function storeTokens($email, $outlook_account, $access_token, $refresh_token, $expires)
    {
        User::where(['email' => $email])->update(array(
            'outlook_account' => $outlook_account,
            'outlook_access_token' => $access_token,
            'outlook_refresh_token' => $refresh_token,
            'outlook_expiry_token' => $expires
        ));
    }

    public function getAccessToken()
    {
        // Check if tokens exist
        if (empty($this->user->outlook_access_token) ||
            empty($this->user->outlook_refresh_token) ||
            empty($this->user->outlook_expiry_token)
        ) {
            return '';
        }

        // Check if token is expired
        //Get current time + 5 minutes (to allow for time differences)
        $now = time() + 300;
        if ($this->user->outlook_expiry_token <= $now) {
            // Token is expired (or very close to it)
            // so let's refresh

            // Initialize the OAuth client
            $oauthClient = new GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUri' => env('OAUTH_REDIRECT_URI'),
                'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
                'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
                'urlResourceOwnerDetails' => '',
                'scopes' => env('OAUTH_SCOPES')
            ]);

            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $this->user->outlook_refresh_token
                ]);

                // Store the new values
                $this->storeTokens($newToken->getToken(), $newToken->getRefreshToken(),
                    $newToken->getExpires());

                return $newToken->getToken();
            } catch (IdentityProviderException $e) {
                return '';
            }
        } else {
            // Token is still valid, just return it
            return $this->user->outlook_access_token;
        }
    }
}
