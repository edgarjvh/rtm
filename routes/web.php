<?php

use App\Exports\MeetingsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/score-ranking', 'ScoreRankingController@showRanking')->name('showRanking');

Route::get('/test', 'RatingsController@test');

Route::get('/registration', 'Auth\RegistrationController@showRegistration')->name('registration');

Route::post('/deleteAccount', 'Auth\RegistrationController@deleteAccount');

Route::post('/join', 'Auth\RegistrationController@showJoin')->name('join');
Route::get('/join', function (){
    return redirect('/registration');
});

Route::post('/create', 'Auth\RegistrationController@showCreate')->name('create');
Route::get('/create', function (){
    return redirect('/registration');
});

Auth::routes(['verify' => true]);

Route::get('verifying/{email}/{token}', 'Auth\VerifyController@verifying')->name('verifying');
Route::get('email/resend/{email}', 'Auth\VerifyController@resendEmail')->name('resendEmail');
Route::get('verifyEmailFirst/{email}', 'Auth\VerifyController@verifyEmailFirst')->name('verifyEmailFirst');

Route::get('/password/request', 'Auth\ResetPasswordController@requestNewPassword')->name('passwordRequest');
Route::post('/password/sent', 'Auth\ResetPasswordController@passwordSent')->name('passwordSent');
Route::get('/password/resetting/{email}/{token}', 'Auth\ResetPasswordController@resetting')->name('resetting');
Route::post('/password/updating', 'Auth\ResetPasswordController@updating')->name('updating');

Route::get('/getstarted', function (){
    return view('getstarted');
});

Route::resource('cal', 'gCalendarController');
Route::get('googleAuth', 'gCalendarController@googleAuth');
Route::get('ref', 'gCalendarController@getByRefreshToken');
Route::get('rtheirm', 'gCalendarController@handleRateTheirMeeting');

Route::resource('/home', 'EventsController')->name('index', 'home');

Route::get('/organization-setup', 'OrganizationController@setOrg');
Route::post('/save-organization', 'OrganizationController@saveOrganization')->name('save-organization');

Route::get('/calendar-authorization', 'MeetingsController@calendarAuthorization');

Route::get('/invite-your-team', 'MeetingsController@showInvitations');
Route::post('/invite-your-team', 'MeetingsController@sendInvitations')->name('invite-your-team');

Route::post('/saveExclusion', 'ExclusionsController@saveExclusion');
Route::post('/deleteExclusion', 'ExclusionsController@deleteExclusion');

Route::post('/setSendingRatingEmails', 'SettingsController@setSendingRatingEmails');
Route::post('/setSendingRatingEmailsAttended', 'SettingsController@setSendingRatingEmailsAttended');
Route::post('/setSharingMeetingScore', 'SettingsController@setSharingMeetingScore');

Route::get('/login/{provider}', 'SocialAuthController@redirectToProvider');
Route::get('/login/{provider}/callback', 'SocialAuthController@handleProviderCallback');

Route::get('/getMeetings', 'MeetingsController@getMeetings');
Route::get('/score/{score}', 'ScoreController@getScore');

Route::get('/ratetheirmeeting', 'RateTheirMeetingController@index')->name('ratetheirmeeting');
Route::post('/ratetheirmeeting', 'RateTheirMeetingController@send')->name('ratetheirmeeting');

Route::get('/rating/{rating_key}/{event_id}/{rate}', 'RatingsController@handleRating')->name('rating');
Route::post('/saveComments', 'CommentsController@saveComment');
Route::post('/getComments', 'CommentsController@getComments');

Route::get('/outlook', 'OutlookController@login');
Route::get('/outlookauth', 'OutlookController@outlookauth');
Route::get('/ocal', 'OutlookController@outlookCalendar')->name('calendar');

Route::post('/updateavatar', 'Auth\VerifyController@updateAvatar')->name('updateAvatar');
Route::post('/deleteTeamMember', 'Auth\VerifyController@deleteTeamMember');


Route::get('/htmltopng', function (){
   return view ('htmltopng');
});

Route::get('/shareonlinkedin', 'SharingController@shareOnLinkedIn')->name('shareonlinkedin');

Route::get('/policy', function (){
    return view('privacy_policies');
});

Route::post('/checkorg', 'OrganizationController@checkorg');

Route::get('/error', 'ErrorController@showMessage')->name('error');

Route::get('/sendEmail/{email}/{rating_key}/{event_id}', 'MessagesController@sendEmail');

Route::get('/getip', 'EventsController@return_ip');

Route::get('/rankingscores', 'ScoreRankingController@showScores');

Route::post('/checkAccount', 'Auth\RegistrationController@checkAccount');

Route::get('/export', function (){
    return Excel::download(new MeetingsExport, 'meetings.csv');
})->middleware('isowner');


// =============== FOR TESTING PURPOSES ================
Route::get('/rate', function (){
    return view('emails.host-event1')->with(['token_host' => 'adakdaukshadudhaksudhkuawhduka']);
});

Route::get('/rate1', function (){
    return view('emails.rate-event1')->with(['rating_key' => 'this-is-a-rating-key', 'event_id' => '554']);
});

Route::get('/jteam', function (){
    return view('emails.join-team')->with(['team_leader_name' => 'Edgar Hernandez', 'token' => 'blablabla']);
});


Route::get('/ver', function (){
    return view('emails.email-verification')->with(['user' => new myUser('edgarjvh@gmail.com', 'dadadhawda7823yehbda7')]);
});

Route::get('/res', function (){
    return view('emails.password-reset')->with(['user' => new myUser('edgarjvh@gmail.com', 'dadadhawda7823yehbda7')]);
});
// ======================================================

class myUser{
    public $email;
    public $verify_token;
    public $password_token;

    public function __construct($email,$token)
    {
        $this->email = $email;
        $this->verify_token = $token;
        $this->password_token = $token;
    }
}