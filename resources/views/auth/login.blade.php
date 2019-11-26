@extends('layouts.app')

@section('content')
    <div class="banner">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="row">
                    <div class="col">
                        <div class="col">
                            <label for="" style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756;">
                                Login
                            </label>
                            <br>
                            <br>
                            <img src="{{Request::root().'/img/login-img.png'}}" alt="">
                        </div>
                    </div>
                    <div class="col">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email" class="m-0">{{ __('E-Mail Address') }}</label>

                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
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

                            <div class="form-group row mb-4">
                                <button type="submit" class="nav-link">
                                    LOGIN
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" style="color: #C96756"
                                       href="{{ route('passwordRequest') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>

                            <hr>

                            <div class="form-group col mt-1 text-center">
                                <label class="control-label col text-center">Or Login With <span
                                            style="color: #C96756; margin-left: 3px"> Google</span></label>

                                <a href="{{ url('login/google') }}"
                                   class="col">
                                    <img src="{{Request::root().'/img/goo_singup.png'}}" style="width: 24px" alt="">
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
