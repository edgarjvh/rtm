@extends('layouts.app')

@section('style-home')
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
@endsection

@section('content')
    <div class="home-container">

        <div class="title">
            <p>
                Hello! <span class="name">{{ Auth::user()->name }}</span>
            </p>
            <p>
                please authorize your calendar
            </p>
        </div>

        <p>
            Choose the calendar you will use with Rate This Meeting
        </p>

        <div class="calendars">

            <div class="calendar-container google">
                <div class="calendar-icon">
                    <img src="{{asset('img/gmail.png')}}" alt="">
                    @if($userLogged)
                        @if($userLogged->google_refresh_token != null)
                            <span class="is-authorized fas fa-check-circle"></span>
                        @endif
                    @endif


                </div>
                <div class="calendar-title">
                    Google Calendar
                </div>

                @if($userLogged)

                    @if($userLogged->google_refresh_token == null)
                        <a href="/googleAuth">
                            Authorize
                        </a>
                    @endif
                @endif

            </div>

            <div class="calendar-container outlook">

                <div class="calendar-icon">
                    <img src="{{asset('img/outlook1.png')}}" alt="">
                    @if($userLogged)

                        @if($userLogged->outlook_access_token != null)
                            <span class="is-authorized fas fa-check-circle"></span>
                        @endif
                    @endif


                </div>
                <div class="calendar-title">
                    Outlook Calendar
                </div>

                @if($userLogged)

                    @if($userLogged->outlook_access_token == null)
                        <a href="/outlookauth">
                            Authorize
                        </a>
                    @endif
                @endif

            </div>
            <div class="calendar-container add">
                <a class="calendar-icon" href="#">
                    <span class="fas fa-plus"></span>
                </a>

                <div class="calendar-title">
                    Other online calendar
                </div>
            </div>
        </div>
    </div>
@endsection
