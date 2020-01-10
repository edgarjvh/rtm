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

    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/moment-timezone.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">
    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/topbar.css') }}" rel="stylesheet">



    @yield('style-register')
    @yield('style-login')
    @yield('style-home')
    @yield('style-verify-email')

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
        }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white mt-2 mb-2">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if(Request::path() !== '/')
                    <img src="{{Request::root() . '/img/logo.png'}}" alt="" style="width: 150px;">
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">REGISTER</a>
                            </li>
                        @endif
                    @else
                        @if(Request::path() === '/')
                            <li class="nav-item">
                                <a class="nav-link" href="/home" role="button">
                                    Home
                                </a>
                            </li>
                        @else
                            {{--<li class="nav-item">--}}
                                {{--<a class="nav-link" href="/events" role="button">--}}
                                    {{--My Meetings--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li class="nav-item dropdown">--}}
                                {{--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"--}}
                                   {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}

                                    {{--@if(!isset($organization) || $organization === '')--}}
                                        {{--{{ Auth::user()->name }}--}}
                                    {{--@else--}}
                                        {{--{{ Auth::user()->name . ' (' . $organization . ')' }}--}}
                                    {{--@endif--}}

                                    {{--<span class="caret"></span>--}}
                                {{--</a>--}}

                                {{--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
                                    {{--<a class="dropdown-item" href="{{ route('logout') }}"--}}
                                       {{--onclick="event.preventDefault();--}}
                                                     {{--document.getElementById('logout-form').submit();">--}}
                                        {{--{{ __('Logout') }}--}}
                                    {{--</a>--}}

                                    {{--<form id="logout-form" action="{{ route('logout') }}" method="POST"--}}
                                          {{--style="display: none;">--}}
                                        {{--@csrf--}}
                                    {{--</form>--}}
                                {{--</div>--}}
                            {{--</li>--}}

                            <li class="nav-item user-profile">
                                <div class="user-info">
                                    <div class="user-name">{{ Auth::user()->name }}</div>
                                    <div class="user-email">{{ Auth::user()->email }}</div>

                                    @if(isset($organization) && $organization !== '')
                                        <div class="user-organization">{{$organization}}</div>
                                    @endif

                                </div>
                                
                                <div class="user-image">
                                    <img src="{{asset('img/default-profile.png')}}" alt="">
                                </div>

                                <a class="logout-btn" href="{{route('logout')}}" title="Log Out">
                                    <span class="fas fa-sign-out-alt"></span>
                                </a>

                            </li>
                        @endif


                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

</body>
</html>
