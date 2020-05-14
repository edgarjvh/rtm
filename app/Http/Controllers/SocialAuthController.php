<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Rating;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Socialite;
use App\User;
use Auth;

class SocialAuthController extends Controller
{
    public $user;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function redirectToProvider($provider)
    {
        $googleScopes = [
            'https://www.googleapis.com/auth/calendar.readonly',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email'
        ];

        $linkedinScopes = [
            'r_liteprofile',
            'r_emailaddress',
            'w_member_social'
        ];

        switch ($provider) {
            case 'linkedin':
                return Socialite::driver($provider)
                    ->scopes($linkedinScopes)
                    ->redirect();
            default:
                return Socialite::driver($provider)
                    ->scopes($googleScopes)
                    ->with(["access_type" => "offline", "prompt" => "select_account"])
//                    ->with(["access_type" => "offline", "prompt" => "consent select_account"])
                    ->redirect();
        }
    }

    public function handleProviderCallback($provider)
    {
        switch ($provider) {
            case 'linkedin':

                try {
                    if (\Illuminate\Support\Facades\Auth::user()) {
                        $this->user = \Illuminate\Support\Facades\Auth::user();

                        if (isset($_GET['code'])) {

                            $client = new Client(['base_uri' => 'https://www.linkedin.com']);
                            $response = $client->request('POST', '/oauth/v2/accessToken', [
                                'form_params' => [
                                    "grant_type" => "authorization_code",
                                    "code" => $_GET['code'],
                                    "redirect_uri" => env('LINKEDIN_CALLBACK_URL'),
                                    "client_id" => env('LINKEDIN_CLIENT_ID'),
                                    "client_secret" => env('LINKEDIN_CLIENT_SECRET'),
                                ],
                            ]);
                            $data = json_decode($response->getBody()->getContents(), true);



                            $access_token = $data['access_token'];

                            $this->user->linkedin_access_token = $access_token;
                            $this->user->linkedin_expiry_token = $data['expires_in'];
                            $this->user->save();

                            $linkedin_profile_id = $this->user->linkedin_id;

                            if (!$linkedin_profile_id){
                                $client = new Client(['base_uri' => 'https://api.linkedin.com']);
                                $response = $client->request('GET', '/v2/me', [
                                    'headers' => [
                                        "Authorization" => "Bearer " . $access_token,
                                    ],
                                ]);
                                $data = json_decode($response->getBody()->getContents(), true);
                                $linkedin_profile_id = $data['id'];
                            }

                            $link = 'https://app.ratethismeeting.com';
                            $linkedin_id = $linkedin_profile_id;
                            $body = new \stdClass();
                            $body->content = new \stdClass();
                            $body->content->contentEntities[0] = new \stdClass();
                            $body->text = new \stdClass();
                            $body->content->contentEntities[0]->thumbnails[0] = new \stdClass();
                            $body->content->contentEntities[0]->entityLocation = $link;

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

                                if (is_numeric($rate)) {
                                    $rates[] = $rate;
                                }
                            }

                            if (count($rates) > 0) {
                                $global_avg = array_sum($rates) / count($rates);
                            } else {
                                $global_avg = 0;
                            }

                            $global_avg = number_format($global_avg, 1);

                            $html = <<<EOD
                                <table style="width: 552px; height:288px;">
                                <tr>
                                    <td>
                                        <div class="score"
                                             style="
                                        padding: 10px;
                                        width: 100px;
                                        height: 100px;
                                        border-radius: 50%;
                                        color: #fff;
                                        background-color: #E56854;
                                        font-family: Source Sans Pro, sans-serif;
                                        font-size: 60px;
                                        line-height: 100px;
                                        text-align: center">
                                            $global_avg
                                                                                    </div>
                                    </td>
                                
                                    <td>
                                        <div class="msg"
                                             style="font-family: Source Sans Pro, sans-serif; display: block; flex-direction: column; padding: 10px;">
                                
                                            <div class="text">
                                                This is my <span style="color:#E56854">Meeting Score</span> on <span
                                                        style="color:#E56854">Rate This Meeting</span><br>
                                                <span style="font-weight: 600">What's yours?</span>
                                            </div>
                                
                                            <div class="logo" style="display: block; text-align: right">
                                                <img src="https://app.ratethismeeting.com/img/logo.png" alt="" style="width: 200px;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </table>
EOD;


                            $data = array('html' => $html);

                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL, "https://hcti.io/v1/image");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

                            curl_setopt($ch, CURLOPT_POST, 1);
                            // Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
                            curl_setopt($ch, CURLOPT_USERPWD, "8fdb2ae4-66f1-4b89-80c9-786225d1fc03" . ":" . "fc24a5d2-e9c3-4f52-8dd2-87303c10eb32");

                            $headers = array();
                            $headers[] = "Content-Type: application/x-www-form-urlencoded";
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                            $result = curl_exec($ch);
                            if (curl_errno($ch)) {
                                echo 'Error:' . curl_error($ch);
                            }
                            curl_close($ch);
                            $res = json_decode($result, true);

                            $body->content->contentEntities[0]->thumbnails[0]->resolvedUrl = $res['url'] . ".png?width=500";
                            $body->content->title = '';
                            $body->owner = 'urn:li:person:' . $linkedin_id;
                            $body->text->text = '';
                            $body_json = json_encode($body, true);

                            $client = new Client(['base_uri' => 'https://api.linkedin.com']);

                            $response = $client->request('POST', '/v2/shares', [
                                'headers' => [
                                    "Authorization" => "Bearer " . $access_token,
                                    "Content-Type" => "application/json",
                                    "x-li-format" => "json"
                                ],
                                'body' => $body_json,
                            ]);

                            if ($response->getStatusCode() !== 201) {
                                echo 'Error: ' . $response->getLastBody()->errors[0]->message;
                            }

                            $shared = 'linkedin';
                            $_SESSION['shared'] = 'not-shared';
                            Redirect::to('/home')->send();
//                            return redirect()->route('home')->withErrors(compact('shared'));
                        }
                    }

                    $userSocial = Socialite::driver($provider)->user();

                    $user = User::firstOrCreate([
                        'email' => $userSocial->email
                    ], [
                        'name' => $userSocial->name,
                        'password' => Hash::make(Str::random(10)),
                        'email_verified_at' => now(),
                        'linkedin_account' => $userSocial->email,
                        'linkedin_access_token' => $userSocial->token,
                        'linkedin_refresh_token' => null,
                        'linkedin_id' => $userSocial->id,
                        'linkedin_expiry_token' => $userSocial->expiresIn,
                        'linkedin_avatar' => $userSocial->avatar,
//                        'organization_id' => 0,
//                        'organization_owner' => $_SESSION['organization_owner']
                    ]);

                    Auth::login($user);
                    $_SESSION['login_type'] = 'linkedin';
                    return redirect('/home');

                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    $_SESSION['shared'] = 'not-shared';
                    Redirect::to('/home')->send();
                }

                break;

            case 'google':

                try {
                    $userSocial = Socialite::driver($provider)->user();

                    $user = User::firstOrCreate([
                        'email' => $userSocial->email
                    ], [
                        'name' => $userSocial->name,
                        'password' => Hash::make(Str::random(10)),
                        'email_verified_at' => now(),
                        'google_account' => $userSocial->email,
                        'google_access_token' => $userSocial->token,
                        'google_refresh_token' => $userSocial->refreshToken,
                        'google_id' => $userSocial->id,
                        'google_expiry_token' => $userSocial->expiresIn,
                        'google_avatar' => $userSocial->avatar,
//                        'organization_id' => 0,
//                        'organization_owner' => $_SESSION['organization_owner']
                    ]);

                    Auth::login($user);
                    $_SESSION['login_type'] = 'google';
                    return redirect('/home');

                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    return redirect('/home');
                }

                break;
            default:
                return redirect('/home');
                break;
        }
    }
}
