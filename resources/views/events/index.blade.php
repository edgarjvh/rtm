@extends('layouts.app')

@section('style-home')
    <link rel="stylesheet" href="{{asset('css/meetings.css')}}">
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('js/meetings.js')}}"></script>
@endsection

@section('content')

    <div class="shared-msg" id="shared-msg">
        Meeting Score has been shared on LinkedIn.
    </div>

    @if(isset($_SESSION['shared']))

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


        {{--@if($_SESSION['shared'] == 'shared')--}}
            {{--<script>--}}
                {{--document.getElementById('shared-msg').className = 'shared';--}}
                {{--document.getElementById('shared-msg').innerHTML = 'Meeting Score has been shared on LinkedIn.';--}}

                {{--setTimeout(function () {--}}
                    {{--$(document).find('#shared-msg').css('height', '35px');--}}
                {{--}, 500);--}}

                {{--setTimeout(function () {--}}
                    {{--$(document).find('#shared-msg').css('height', 0);--}}
                {{--}, 5000);--}}
            {{--</script>--}}
        {{--@else--}}
            {{--<script>--}}
                {{--document.getElementById('shared-msg').className = 'not-shared';--}}
                {{--document.getElementById('shared-msg').innerHTML = 'An error occured while trying to share on LinkedIn. Please try again in a few minutes.';--}}

                {{--setTimeout(function () {--}}
                    {{--$(document).find('#shared-msg').css('height', '35px');--}}
                {{--}, 500);--}}

                {{--setTimeout(function () {--}}
                    {{--$(document).find('#shared-msg').css('height', 0);--}}
                {{--}, 5000);--}}
            {{--</script>--}}
        {{--@endif--}}

    @endif

    @unset($_SESSION['shared'])

    <div class="events-bg"></div>

    <div class="main-container">
        <div class="meeting-score">
            <div class="title">My Meeting Score</div>

            <div class="score">

                <div class="score-value">
                    {{number_format($global_avg[0]->score, 2)}}
                </div>

                <div class="sharing">
                    <a href="{{env('APP_URL').'/shareonlinkedin/'}}" class="linkedin">
                        <img src="{{asset('img/linkedin2.png')}}" alt="" style="max-width: 100%">
                    </a>
                </div>
                {{--<div class="sharing">--}}
                    {{--<a href="{{env('APP_URL').'/shareonlinkedin/'}}" class="linkedin">--}}
                        {{--<span class="fab fa-linkedin-in"></span> Share--}}
                    {{--</a>--}}
                {{--</div>--}}
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
                    <div class="tbl">
                        <div class="thead">
                            <div class="trow">
                                <div class="tcol organizer">Organizer</div>
                                <div class="tcol title">Title</div>
                                <div class="tcol start-date">Start Date</div>
                                <div class="tcol end-date">End Date</div>
                                <div class="tcol rate">Rate</div>

                                @if($userLogged->organization_owner == 1)
                                    <div class="tcol export-icon">
                                        <a href="/export">
                                            <span class="fas fa-file-csv"></span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tbody">
                            @if(count($newEvents) > 0)
                                @foreach($newEvents as $event)
                                    <div class="trow">

                                        <div class="event-info">
                                            <div class="tcol event-id">{{$event->event_id}}</div>
                                            <div class="tcol organizer">
                                                <b>{{$event->name}}</b>
                                                <br>
                                                <small>{{$event->organizer}}</small>
                                            </div>
                                            <div class="tcol title">
                                                {{$event->title}}
                                            </div>
                                            <div class="tcol start-date">
                                                {{(new DateTime($event->start_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}
                                            </div>
                                            <div class="tcol end-date">
                                                {{(new DateTime($event->end_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}
                                            </div>
                                            <div class="tcol rate">
                                                <span class="rate-responses">{{number_format($event->score,2)}}</span>
                                                ({{$event->responses}})
                                                <i class="fas fa-comment-dots btn-comment" title="Comments"></i>
                                                <i class="fas fa-spin fa-spinner btn-loading"></i>
                                            </div>
                                            @if($userLogged->organization_owner == 1)
                                                <div class="tcol export-icon"></div>
                                            @endif
                                        </div>

                                        <div class="event-comments"></div>


                                    </div>
                                @endforeach
                            @else
                                <div class="trow">
                                    <div class="tcol no-meetings">No meetings to show</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-mobile">
                    @if(count($newEvents) > 0)
                        @foreach($newEvents as $event)
                            <div class="trow">
                                <div class="event-info">
                                    <div class="tcol tcol-left">
                                        <div class="tcol-line event-id">
                                            {{$event->event_id}}
                                        </div>
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
                                            <span class="tcol-data"><span
                                                        class="rate-responses">{{number_format($event->score,2)}}</span> ({{$event->responses}}
                                                )</span>
                                            <i class="fas fa-comment-dots btn-comment-mobile" title="Comments"></i>

                                            <i class="fas fa-spin fa-spinner btn-loading"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-comments">
                                    <div class="comment-container">
                                        <div class="date-time">comment.created_at</div>

                                        <div class="content">comment.comment</div>
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
                    <div class="tbl-team">
                        <div class="thead">
                            <div class="trow">
                                <div class="tcol team-member">Team Member</div>
                                <div class="tcol member-score">Meeting Score</div>
                                <div class="tcol action"></div>
                            </div>
                        </div>

                        <div class="tbody">

                            @foreach($teamMembers as $member)
                                <div class="trow member">
                                    <div class="trow-wrapper">
                                        <input class="member-id" type="hidden" value="{{$member->id}}">
                                        <div class="tcol team-member">
                                            <div class="avatar-container">
                                                @if($member->avatar)
                                                    <img src="{{Storage::url($member->avatar)}}" alt="">
                                                @else
                                                    <img src="{{asset('img/default-profile.png')}}" alt="">
                                                @endif
                                            </div>
                                            <span class="user-email">{{$member->email}}</span>
                                        </div>

                                        <div class="tcol member-score">
                                            @if($member->sharing_meeting_score == 1)
                                                {{number_format($member->score, 2)}}
                                            @else
                                                not sharing
                                            @endif
                                        </div>

                                        <div class="tcol action">
                                            @if($member->organization_owner == 1)
                                                <span class="action-you">You</span>
                                            @else
                                                <span class="fas fa-times action-delete-user"></span>
                                                <span class="action-delete-user" title="Delete {{$member->email}}">Delete User</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="trow">
                                <div class="trow-wrapper">
                                    <a href="{{route('invite-your-team')}}" class="tcol add-team-member">
                                        <span class="fas fa-plus"></span>
                                        <span>Add team member</span>
                                    </a>
                                </div>
                            </div>

                            <div class="trow">
                                <div class="trow-wrapper">
                                    <div class="tcol members-left">
                                        <p>
                                            {{--<span class="highlighted1">You have <span class="members-counter">{{10 - count($teamMembers)}}</span> members left to add.</span>--}}
                                            <span>If you want to grow your team</span>
                                            <a href="https://ratethismeeting.me/plans" target="_blank" class="highlighted2">upgrade now</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <strong>Blocked users won't rate your meetings!</strong> (They won't receive any rating email from you).
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

                    {{--<div class="trow">--}}
                        {{--<div class="tbtn transfer-account" title="Transfer my account">--}}
                            {{--Transfer my account--}}
                        {{--</div>--}}

                        {{--<div class="tbtn delete-account" title="Delete my account">--}}
                            {{--Delete my account--}}
                        {{--</div>--}}

                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>

    @if(isset($shared))
        <p>Your Meeting Score have been shared on LinkedIn successfully</p>
    @endif

@endsection