<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['login_type'])){
    \Illuminate\Support\Facades\Redirect::to('/home')->send();
}

$user = \Illuminate\Support\Facades\Auth::user();


if (isset($_GET['th'])) {
    $_SESSION['registration_type'] = 'other';
    $_SESSION['th'] = $_GET['th'];

    $newUser = \App\User::where('token_host', $_GET['th'])->first();

    if ($newUser) {
        $_SESSION['user_email'] = $newUser->email;
        $_SESSION['user_name'] = $newUser->name;
        $_SESSION['organization_owner'] = 1;
    } else {
        \Illuminate\Support\Facades\Redirect::to('/login')->send();
    }
}else{
    $_SESSION['organization_owner'] = 1;
}
?>

@extends('layouts.app')

@section('style-register')
    <link rel="stylesheet" href="{{asset('css/register-profile.css')}}">
    <script type="text/javascript" src="{{asset('js/register-profile.js')}}"></script>
@endsection

@section('content')
    <div class="profile-container">

        <div class="reg-options">
            <div class="opt col">
                <div class="title">
                    First, enter your name
                </div>
                <img src="img/reg-opt-2.png" alt="">
            </div>

            <div class="opt">
                <form method="POST" action="{{ route('register') }}" autocomplete="off">
                    @csrf

                    @if(isset($_SESSION['registration_type']) && $_SESSION['registration_type'] != 'email')
                        @if($_SESSION['registration_type'] == 'other')
                            <div class="form-group">
                                <label for="name" class="m-0">{{ __('Name') }}</label>

                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror" name="name"
                                       value="{{ $_SESSION['user_name'] }}" required autocomplete="name" autofocus>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="name" class="m-0">{{ __('Name') }}</label>

                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror" name="name"
                                       value="{{ $_SESSION['user_name'] }}" required autocomplete="name" autofocus
                                       readonly>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="email"
                                   class="m-0">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ $_SESSION['user_email'] }}"
                                   required autocomplete="email" readonly>

                            <span class="email-msg"></span>
                        </div>

                    @else
                        <div class="form-group">
                            <label for="name" class="m-0">{{ __('Name') }}</label>

                            @if (!empty($name))
                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror" name="name"
                                       value="{{ $name }}" required autocomplete="name" autofocus>
                            @else
                                <input id="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror" name="name"
                                       value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email"
                                   class="m-0">{{ __('E-Mail Address') }}</label>

                            @if (!empty($email))
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ $email }}"
                                       required autocomplete="email">
                            @else
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required autocomplete="email">
                            @endif

                            <span class="email-msg"></span>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="password"
                               class="mb-0">{{ __('Password') }}</label>

                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               required autocomplete="new-password">

                        <span class="pass-msg"></span>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm"
                               class="m-0">{{ __('Confirm Password') }}</label>

                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required autocomplete="new-password">

                        <span class="conf-msg"></span>
                    </div>

                    <input type="hidden" id="owner" name="owner" value="1">

                    @if(isset($_GET['token']))
                        <input type="hidden" id="tokenteam" name="tokenteam" value="{{$_GET['token']}}">
                    @else
                        <input type="hidden" id="tokenteam" name="tokenteam" value="0">
                    @endif

                    <div class="form-group submit">
                        <button type="submit" class="nav-link">
                            REGISTER
                        </button>
                    </div>

                    <input type="hidden" name="type" value="owner">
                </form>

                <div class="or-login-with">
                    <div class="line-container">
                        <div class="left-line"></div>
                        <div class="center-line">Or</div>
                        <div class="right-line"></div>
                    </div>
                </div>

                <div class="social-buttons">
                    <a href="{{url('/login/google')}}">
                        <img src="{{asset('img/google.png')}}" alt=""> Continue with Google
                    </a>

                    <a href="{{url('/login/linkedin')}}">
                        <img src="{{asset('img/linkedin.png')}}" alt=""> Continue with LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </div>


@endsection
