@extends('layouts.app')

@section('style-home')
    <link rel="stylesheet" href="{{asset('css/meetings.css')}}">
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('js/meetings.js')}}"></script>
@endsection

@section('content')
    @csrf



    <div class="shared-msg" id="shared-msg">
        Meeting Score has been shared on LinkedIn.
    </div>

    @if(isset($_SESSION['shared']))
        @if($_SESSION['shared'] == 'shared')
            <script>
                document.getElementById('shared-msg').className = 'shared';
                document.getElementById('shared-msg').innerHTML = 'Meeting Score has been shared on LinkedIn.';

                setTimeout(function () {
                    $(document).find('#shared-msg').css('height', '35px');
                }, 500);

                setTimeout(function () {
                    $(document).find('#shared-msg').css('height', 0);
                }, 5000);
            </script>
        @else
            <script>
                document.getElementById('shared-msg').className = 'not-shared';
                document.getElementById('shared-msg').innerHTML = 'An error occured while trying to share on LinkedIn. Please try again in a few minutes.';

                setTimeout(function () {
                    $(document).find('#shared-msg').css('height', '35px');
                }, 500);

                setTimeout(function () {
                    $(document).find('#shared-msg').css('height', 0);
                }, 5000);
            </script>
        @endif

    @endif

    @unset($_SESSION['shared'])

    <div class="events-bg"></div>

    <div class="main-container">
        <div class="meeting-score">
            <div class="title">My Meeting Score</div>

            <div class="score">

                <div class="score-value">
                    {{number_format($global_avg, 2)}}
                </div>

                <div class="sharing">
                    <a href="{{env('APP_URL').'/shareonlinkedin/'}}" class="linkedin">
                        <span class="fab fa-linkedin-in"></span> Share
                    </a>
                </div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab tab-my-meetings active" data-name="tab-my-meetings">
                My Meetings
            </div>
            @if($userLogged->organization_owner == 1)
                <div class="tab tab-team" data-name="tab-team">
                    Team
                </div>
            @endif
            <div class="tab tab-settings" data-name="tab-settings">
                Settings
            </div>
        </div>

        <div class="tabs-container">
            <div class="tab-content tab-my-meetings active">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Provider</th>
                            <th scope="col">Organizer</th>
                            <th scope="col">Title</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Rate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($newEvents) > 0)
                            @foreach($newEvents as $event)
                                <tr>
                                    <td class="align-middle"><img
                                                src="{{'/img/'.strtolower($event->provider) . '.png'}}"
                                                style="width:16px; margin-top:-5px"
                                                alt=""> {{ ucwords($event->provider) }}</td>
                                    <td class="align-middle">
                                        <b>{{$event->name}}</b>
                                        <br>
                                        <small>{{$event->organizer}}</small>
                                    </td>
                                    <td class="align-middle">  {{$event->title}}</td>
                                    <td class="align-middle">{{(new DateTime($event->start_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</td>
                                    <td class="align-middle">{{(new DateTime($event->end_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</td>
                                    <td class="align-middle">{{$event->rate === null ? 'unrated' : number_format($event->rate,1)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">No meetings to show</td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            {{--<td colspan="6">{{ $newEvents->links() }}</td>--}}
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="table-mobile">
                    @if(count($newEvents) > 0)
                        @foreach($newEvents as $event)
                            <div class="trow">
                                <div class="tcol tcol-left">
                                    <div class="tcol-line">
                                        <span class="tcol-title">Provider</span>
                                        <span class="tcol-data">{{ ucwords($event->provider) }}</span>
                                    </div>
                                    <div class="tcol-line">
                                        <span class="tcol-title">Organizer</span>
                                        <span class="tcol-data">{{$event->name}}</span>
                                    </div>
                                    <div class="tcol-line">
                                        <span class="tcol-title">Title</span>
                                        <span class="tcol-data">{{$event->title}}</span>
                                    </div>
                                </div>

                                <div class="tcol tcol-right">
                                    <div class="tcol-line">
                                        <span class="tcol-title">Start Date</span>
                                        <span class="tcol-data">{{(new DateTime($event->start_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</span>
                                    </div>
                                    <div class="tcol-line">
                                        <span class="tcol-title">End Date</span>
                                        <span class="tcol-data">{{(new DateTime($event->end_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</span>
                                    </div>
                                    <div class="tcol-line">
                                        <span class="tcol-title">Rate</span>
                                        <span class="tcol-data">{{$event->rate === null ? 'unrated' : number_format($event->rate,1)}}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else

                    @endif
                </div>

            </div>

            @if($userLogged->organization_owner == 1)
                <div class="tab-content tab-team">
                    Tab Team
                </div>
            @endif

            <div class="tab-content tab-settings">
                <div class="tbl-settings">
                    <div class="trow">
                        <div class="check-container">
                            @if($settings->sending_rating_emails == 1)
                                <input type="checkbox" id="cbox-set-send-rating-emails" checked>
                            @else
                                <input type="checkbox" id="cbox-set-send-rating-emails">
                            @endif

                            <label for="cbox-set-send-rating-emails">
                                <span class="fas fa-check"></span>
                            </label>
                        </div>

                        <label for="cbox-set-send-rating-emails">
                            Send automatic rating emails to all attendees of meetings I hosted.
                        </label>

                        <span class="fas fa-spin fa-spinner loader"></span>
                    </div>

                    <div class="trow">
                        <div class="check-container">
                            @if($settings->sending_rating_emails == 1)
                                <input type="checkbox" id="cbox-set-send-rating-emails-attended" checked>
                            @else
                                <input type="checkbox" id="cbox-set-send-rating-emails-attended">
                            @endif

                            <label for="cbox-set-send-rating-emails-attended">
                                <span class="fas fa-check"></span>
                            </label>
                        </div>

                        <label for="cbox-set-send-rating-emails-attended">
                            Send automatic rating emails to all attendees of meetings I attended.
                        </label>

                        <span class="fas fa-spin fa-spinner loader"></span>
                    </div>

                    <div class="trow">
                        <div class="check-container">
                            @if($settings->sharing_meeting_score == 1)
                                <input type="checkbox" id="cbox-set-allow-sharing" checked>
                            @else
                                <input type="checkbox" id="cbox-set-allow-sharing">
                            @endif
                            <label for="cbox-set-allow-sharing">
                                <span class="fas fa-check"></span>
                            </label>
                        </div>

                        <label for="cbox-set-allow-sharing">
                            Allow to share my meeting score.
                        </label>

                        <span class="fas fa-spin fa-spinner loader"></span>
                    </div>

                    <div class="trow">
                        <div class="check-container">
                            @if($userLogged->google_access_token == null)
                                <input type="checkbox" id="cbox-set-google-calendar" class="not-clickable">
                            @else
                                <input type="checkbox" id="cbox-set-google-calendar" class="not-clickable" checked>
                            @endif

                            <label for="cbox-set-google-calendar" class="not-clickable">
                                <span class="fas fa-check"></span>
                            </label>
                        </div>

                        <label for="cbox-set-google-calendar" class="not-clickable">
                            <img src="{{asset('img/goo_singup.png')}}" alt="">
                            Google Calendar.
                        </label>

                        @if($userLogged->google_access_token == null)
                            <a href="/googleAuth">
                                Authorize
                            </a>
                        @endif
                    </div>

                    <div class="trow">
                        <div class="check-container">
                            @if($userLogged->outlook_access_token == null)
                                <input type="checkbox" id="cbox-set-outlook-calendar" class="not-clickable">
                            @else
                                <input type="checkbox" id="cbox-set-outlook-calendar" class="not-clickable" checked>
                            @endif
                            <label for="cbox-set-outlook-calendar" class="not-clickable">
                                <span class="fas fa-check"></span>
                            </label>
                        </div>

                        <label for="cbox-set-outlook-calendar" class="not-clickable">
                            <img src="{{asset('img/outlook1.png')}}" alt="">
                            Outlook Calendar.
                        </label>

                        @if($userLogged->outlook_access_token == null)
                            <a href="/outlookauth">
                                Authorize
                            </a>
                        @endif
                    </div>

                    <div class="trow">
                        <div class="exclusions-container">
                            <p>
                                <strong>This users are blocked</strong> (They won't receive any rating email from you).
                            </p>

                            <div class="excluded-email-list">
                                @foreach($exclusions as $exclusion)
                                    <div class="exclusion">
                                        <div class="input">{{$exclusion->email}}</div>

                                        <div class="actions">
                                            <span class="fas fa-check-circle check" title="save"></span>
                                            <span class="fas fa-minus-circle minus" title="delete"></span>
                                            <span class="fas fa-times-circle times" title="cancel"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="btn-add-exclusion">
                                <div>
                                    <span class="fas fa-plus"></span>
                                </div>
                                Add a blocked user
                            </div>
                        </div>
                    </div>

                    <div class="trow">
                        <div class="tbtn transfer-account" title="Transfer my account">
                            Transfer my account
                        </div>

                        <div class="tbtn delete-account" title="Delete my account">
                            Delete my account
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($shared))
        <p>Your Meeting Score have been shared on LinkedIn successfully</p>
    @endif

@endsection