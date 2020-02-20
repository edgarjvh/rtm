<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/test', 'RatingsController@test');

Route::get('/join', function (){
    return view('auth.join');
});
Route::get('/create', function (){
    return view('auth.create');
});

Auth::routes(['verify' => true]);

Route::get('verifyEmailFirst/{email}', 'Auth\RegisterController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('verifying/{email}/{token}', 'Auth\RegisterController@verifying')->name('verifying');
Route::get('email/resend/{email}', 'Auth\RegisterController@resendEmail')->name('resendEmail');

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

Route::resource('/home', 'EventsController');

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

Route::get('/rating/{rating_key}/{event_id}/{rate}', 'RatingsController@handleRating')->name('rating');

Route::get('/outlook', 'OutlookController@login');
Route::get('/outlookauth', 'OutlookController@outlookauth');
Route::get('/ocal', 'OutlookController@outlookCalendar')->name('calendar');

Route::get('/policy', function (){
    return view('privacy_policies');
});

Route::post('/checkorg', 'OrganizationController@checkorg');

Route::get('/error', 'ErrorController@showMessage')->name('error');

Route::get('/rate', function (){
    return view('emails.rate-event1')->with(['rating_key' => 'this-is-a-rating-key', 'event_id' => '554']);
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

Route::get('/sendEmail/{email}/{rating_key}/{event_id}', 'MessagesController@sendEmail');

Route::get('/getip', 'EventsController@return_ip');






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