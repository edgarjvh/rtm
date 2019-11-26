@extends('layouts.app')

@section('content')
    <div class="banner" style="background-image: url({{Request::root() . '/img/ban.png'}})"></div>
    <div class="container h-100 d-flex flex-column">
        <div class="row flex-grow-1 justify-content-center align-items-center p-4">
            <div class="col-md-4" style="margin-top: -100px">
                <div class="col">
                    <div style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756;text-align: center;margin-bottom: 15px;">
                        {{ __('Reset Password') }}
                    </div>

                    <form method="POST" action="{{ route('updating') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group ">
                            <label for="email"
                                   class="m-0">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @if(isset($email_error_message))
                                <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $email_error_message }}</strong>
                                    </span>
                            @endif

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label for="password" class="m-0">{{ __('Password') }}</label>

                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="new-password">

                            @if(isset($password_error_message))
                                <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $password_error_message }}</strong>
                                    </span>
                            @endif

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label for="password-confirm" class="m-0">{{ __('Confirm Password') }}</label>

                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="nav-link btn-block m-0">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
