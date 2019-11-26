@extends('layouts.app')

@section('content')
    <div class="banner"></div>
    <div class="container h-100 d-flex flex-column">
        <div class="row flex-grow-1 justify-content-center align-items-center p-4">
            <div class="col-md-4" style="margin-top: -100px">
                <div class="col">
                    <div style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756;text-align: center;margin-bottom: 15px;">{{ __('Reset Password') }}</div>

                    <form method="POST" action="{{ route('passwordSent') }}">
                        @csrf

                        <div class="form-group">
                            @if ($expired)
                                <div class="alert alert-danger" role="alert">
                                    {{ __('Password reset link has expired.') }}
                                </div>
                            @else
                                @if($resend)
                                    <div class="alert alert-success" role="alert">
                                        We have e-mailed your password reset link!
                                    </div>
                                @endif
                            @endif

                            <label for="email"
                                   class="mb-0">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email"
                                   class="form-control d-block @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="nav-link btn-block m-0">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
