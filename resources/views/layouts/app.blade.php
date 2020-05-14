<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rate This Meeting') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/topbar.js') }}" defer></script>

    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/moment-timezone.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/topbar.css') }}" rel="stylesheet">


    @yield('style-register')
    @yield('style-reg-org')
    @yield('style-login')
    @yield('style-home')
    @yield('style-verify-email')
    @yield('style-invite-team')
    @yield('style-rate-their-meeting')
    @yield('style-ranking')
    @yield('style-rated')


    <style>
        #app {
            display: flex;
            flex-direction: column;
            height: 100vh;

        }

        .nav-link {
            border: 2px solid #C96756;
            min-width: 90px !important;
            margin-left: 15px;
            text-align: center;
            color: #C96756 !important;
            border-radius: 5px;
            padding: 2px 15px !important;
            background-color: transparent;
            cursor: pointer;
        }

        @media screen and (max-width: 767px) {
            .nav-link {
                border: 2px solid #C96756;
                min-width: 72px;
                margin-left: 0;
                margin-top: 5px;
                text-align: center;
                color: #C96756 !important;
                border-radius: 5px;
                padding: 2px 15px !important;
            }
        }

        input {
            background-color: #FAFAFA !important;
        }

        main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            position: relative;
            background-color: #EDEEF0 !important;
        }

    </style>
</head>
<body>

<div class="modal-container">
    <div class="modal-wrapper">
        <div class="home-modal">
            <p>
                Are you sure to delete your account?
            </p>

            <div class="modal-footer">
                <div class="modal-cancel-btn">
                    Cancel
                </div>

                <div class="modal-delete-btn">
                    Delete
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sidemenu-modal"></div>

<label class="topbar-toggle-btn" for="cbox-toggle-menu">
    <span class="fas fa-bars"></span>
</label>

<input type="checkbox" id="cbox-toggle-menu">

<div class="sidebar-container">
    <div class="sidemenu">
        @guest
            <div class="sidebar-item">
                <a class="sidebar-link" href="{{ route('login') }}">Login</a>
            </div>
            @if (Route::has('register'))
                <div class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('registration') }}">Register</a>
                </div>
            @endif
        @else
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>

                    @if(isset($organization) && $organization !== '')
                        <div class="user-organization">{{$organization}}</div>
                    @endif

                </div>

                <div class="user-image">
                    @if(isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'google')
                        @if (Auth::user()->google_avatar)
                            <img src="{{Auth::user()->google_avatar}}" alt="">
                        @else
                            <img src="{{asset('img/default-profile.png')}}" alt="">
                        @endif
                    @elseif(isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'linkedin')
                        @if (Auth::user()->linkedin_avatar)
                            <img src="{{Auth::user()->linkedin_avatar}}" alt="">
                        @else
                            <img src="{{asset('img/default-profile.png')}}" alt="">
                        @endif
                    @else
                        <img src="{{asset('img/default-profile.png')}}" alt="">
                    @endif
                </div>

                <a href="#" class="change-user-image" title="Change User Image">
                    <span class="fas fa-camera"></span>
                </a>
            </div>

            <div class="sidebar-item">
                <a class="sidebar-link" href="/invite-your-team">Invite Team Members</a>
            </div>

            <div class="sidebar-item">
                <a class="sidebar-link logout-btn" href="{{route('logout')}}" title="Log Out"
                   onclick="
                            event.preventDefault();
                            document.getElementById('logout-form').submit();"
                >
                    Log Out
                </a>
            </div>
        @endguest
    </div>
</div>

<div id="app">

    <div class="topbar">
        <div class="top-container">
            <a href="{{ url('/') }}" class="brand-reg">
                @if(Request::path() !== '/')
                    <img src="{{Request::root() . '/img/logo.png'}}" alt="" style="width: 200px;">
                @endif
            </a>

            <a href="{{ url('/') }}" class="brand-res">
                @if(Request::path() !== '/')
                    <img src="{{Request::root() . '/img/iconx100.png'}}" alt="" style="width: 30px;">
                @endif
            </a>

            <div class="topbar-collapse-menu">
                @guest
                    <div class="topbar-item">
                        <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                    </div>
                    @if (Route::has('register'))
                        <div class="topbar-item">
                            <a class="nav-link" href="{{ route('registration') }}">REGISTER</a>
                        </div>
                    @endif
                @else
                    @if(Request::path() === '/')
                        <div class="topbar-item">
                            <a class="topbar-link" href="/home" role="button">
                                Home
                            </a>
                        </div>
                    @else

                        <div class="topbar-item user-profile">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-email">{{ Auth::user()->email }}</div>

                                @if(isset($organization) && $organization !== '')
                                    <div class="user-organization">{{$organization}}</div>
                                @endif

                            </div>

                            <div class="user-image">
                                @if(isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'google')
                                    @if (Auth::user()->google_avatar)
                                        <img src="{{Auth::user()->google_avatar}}" alt="">
                                    @else
                                        <img src="{{asset('img/default-profile.png')}}" alt="">
                                    @endif
                                @elseif(isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'linkedin')
                                    @if (Auth::user()->linkedin_avatar)
                                        <img src="{{Auth::user()->linkedin_avatar}}" alt="">
                                    @else
                                        <img src="{{asset('img/default-profile.png')}}" alt="">
                                    @endif
                                @else
                                    <img src="{{asset('img/default-profile.png')}}" alt="">
                                @endif
                            </div>

                            <a href="#" class="change-user-image" title="Change User Image">
                                <span class="fas fa-camera"></span>
                            </a>
                        </div>

                    @endif

                    <div class="logout-container">
                        <a href="/invite-your-team">Invite Team Members</a>

                        <a class="logout-btn" href="{{route('logout')}}" title="Log Out"
                           onclick="
                            event.preventDefault();
                            document.getElementById('logout-form').submit();"
                        >
                            Log Out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>

                    </div>
                @endguest
            </div>
        </div>
    </div>

    <main class="py-4">
        @yield('content')
    </main>
</div>

</body>
</html>
