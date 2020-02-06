@extends('layouts.app')

@section('style-login')
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')
    <div class="login-container">
        <div class="log-options">
            <div class="opt col">
                <div class="title">
                    Login
                </div>
                <img src="{{asset('img/login.png')}}" alt="">
            </div>
            <div class="opt">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="m-0">{{ __('E-Mail Address') }}</label>

                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror text-lowercase" name="email"
                               value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="m-0">{{ __('Password') }}</label>

                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group row pl-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group submit">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link"
                               href="{{ route('passwordRequest') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif

                        <button type="submit" class="nav-link">
                            LOGIN
                        </button>
                    </div>
                </form>

                <div class="or-login-with">
                    <div class="line-container">
                        <div class="left-line"></div>
                        <div class="center-line">Or Login With</div>
                        <div class="right-line"></div>
                    </div>
                </div>

                <div class="social-buttons">
                    <a href="{{url('/login/linkedin')}}" title="LinkedIn">
                        <span class="fab fa-linkedin"></span>
                    </a>

                    <a href="{{url('/login/google')}}" title="Google">
                        <span class="fab fa-google"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
