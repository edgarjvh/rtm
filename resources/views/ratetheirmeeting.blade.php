@extends('layouts.app')

@section('style-rate-their-meeting')
    <link rel="stylesheet" href="{{asset('css/rate-their-meeting.css')}}">
@endsection

@section('content')
    <div class="main-container">
        <div class="events-bg"></div>

        @if(isset($msg))
            <div class="msg">
                {{$msg}}
            </div>
        @endif

        <div class="msg">
            Thanks for submitting your rating!
        </div>

        <div class="opt-container">
            <div class="opt">
                <div class="title">
                    Rate Their Meeting
                </div>
                <div class="subtitle">
                    Bad meeting? Make them accountable!
                </div>
                <img src="{{asset('img/invite-team.png')}}" alt="">
            </div>

            <div class="opt">
                <form action="{{route('ratetheirmeeting')}}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="host-email">Host Email</label>
                        <input type="email" id="host-email" name="hostemail" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="attendees-email">Attendees Email (comma separated)</label>
                        <textarea type="text" id="attendees-email" name="attendeesemail" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="host-linkedin-email">Host LinkedIn Email (Optional)</label>
                        <input type="email" id="host-linkedin-email" name="hostlinkedin" class="form-control">
                    </div>

                    <div class="form-group submit">
                        <input type="submit" value="Send" class="form-control" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection