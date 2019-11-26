@extends('layouts.app')

@section('content')
    <div class="dashboard-bg"></div>
    <div class="container h-100 d-flex flex-column">
        <div class="row flex-grow-1 justify-content-center align-items-center p-4">
            <div class="col text-center" style="margin-top: -150px">
                <div style="font-family: 'Merriweather', Serif; font-size: 30px;color: #C96756;">Dashboard</div>


                <div style="font-family: 'Source Code Pro',sans-serif; font-size: 1.4rem; color: rgba(0,0,0,0.4);margin: 10px 0">
                    You are now logged in!
                </div>

                <div class="btn-container d-flex flex-row justify-content-center">
                    @if($userLogged)

                        @if($userLogged->google_access_token == null)
                            <a href="/googleAuth" class="dash-link mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">
                                Authorize your Google Calendar
                            </a>
                        @else
                            <a href="#" class="dash-link authorized mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">
                                Google Calendar is authorized
                            </a>
                        @endif

                        @if($userLogged->outlook_access_token == null)
                            <a href="/outlookauth" class="dash-link mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">
                                Authorize your Outlook Calendar
                            </a>
                        @else
                            <a href="#" class="dash-link authorized mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">Outlook Calendar is authorized</a>
                        @endif

                        @if($userLogged->apple_access_token == null)
                            <a href="#" class="dash-link mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">Authorize your Apple Calendar</a>
                        @else
                            <a href="#" class="dash-link authorized mt-2 ml-1 mr-1 flex-grow-1 font-weight-bold">Apple Calendar is authorized</a>
                        @endif
                    @endif
                </div>


            </div>
        </div>
    </div>
@endsection
