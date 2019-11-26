<?php

namespace App\Console\Commands;

use App\Event;
use App\Mail\RateEvent;
use App\RatingKey;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class cronRatingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user){
            if ($user->google_refresh_token != null) {
                $client = new Google_Client();
                $client->setAuthConfig('client_secret.json');
                $client->setAccessType('offline');
                $client->addScope(Google_Service_Calendar::CALENDAR);

                $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
                $client->setHttpClient($guzzleClient);

                $client->refreshToken(addslashes($user->google_refresh_token));
                $client->getAccessToken();

                $service = new Google_Service_Calendar($client);

                $calendarId = 'primary';

                $optParams = array(
                    'maxResults' => 10,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'timeMin' => date('c', strtotime('first day of this month'))
                );

                $results = $service->events->listEvents($calendarId, $optParams);

                if (count($results->getItems()) > 0) {

                    for ($i = 0; $i < count($results->getItems()); $i++) {
                        // validate events as meeting
                        $item = $results->getItems()[$i];
                        $now = time();

                        // check if event is not set for all day
                        if ($item['start']['dateTime'] && $item['end']['dateTime']) {
                            $dtStart = strtotime($item['start']['dateTime']);
                            $dtEnd = strtotime($item['end']['dateTime']);

                            // is meeting over in less than 1 day
                            if ((ceil(($now - $dtEnd) / 60) >= 0) && ((ceil($now - $dtEnd) / 60) < 1440)) {

                                // is meeting duration is greater than 20 minutes but less than 1 day
                                if ((ceil(($dtEnd - $dtStart) / 60) > 20) && (ceil(($dtEnd - $dtStart) / 60) < 1440)) {

                                    // check if user is also creator
                                    if ($user->email == $item['organizer']['email']) {

                                        // has 2 or more attendees
                                        if (($item['attendees']) && count($item['attendees']) > 1) {

                                            for ($x = 0; $x < count($item['attendees']); $x++) {
                                                $attendee = $item['attendees'][$x];

                                                // if attendee is not the organizer, sent email for rating
                                                if ($item['organizer']['email'] != $attendee['email']) {
                                                    $rating_key = Str::random(100);
                                                    $eventExist = Event::where('event_id', $item['id'])->first();

                                                    // if event is not in database, will be saved
                                                    if (!$eventExist) {
                                                        $event = new Event();
                                                        $event->organizer = $item['organizer']['email'];
                                                        $event->start_date = date('Y-m-d H:i:s', $dtStart);
                                                        $event->end_date = date('Y-m-d H:i:s', $dtEnd);
                                                        $event->attendees = count($item['attendees']);
                                                        $event->event_id = $item['id'];
                                                        $event->provider = 'google';
                                                        $event->title = $item['summary'];
                                                        $event->description = $item['description'];
                                                        $event->save();
                                                    }

                                                    $key = new RatingKey();
                                                    $key->rating_key = $rating_key;
                                                    $key->save();

                                                    app()->call('\App\Http\Controllers\MessagesController@sendEmail',
                                                        [$attendee['email'],
                                                            $rating_key,
                                                            $item['id']]);

                                                    $domain = 'http://ratemymeeting.webilation.com/rating';
                                                    Mail::to($attendee['email'])->send(new RateEvent($domain, $rating_key, $item['id']));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
