@extends('layouts.app')

@section('style-verify-email')
    <link rel="stylesheet" href="{{asset('css/verify-email.css')}}">
@endsection

@section('content')
    <div class="container h-100 d-flex flex-column">
        <div class="row flex-grow-1 justify-content-center align-items-center p-4">
            
            <div class="col-md-8 text-center">
                <img src="{{asset('img/reg-verify-email.png')}}" alt="">

                <div class="col mt-3">
                    <div class="title" style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756;">{{ __('Verify Your Email Address') }}</div>

                    <p style="font-family: 'Source Sans Pro',sans-serif; font-size: 1.1rem" class="mt-2">
                        @if (isset($resend))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        Before proceeding, please check your email for a verification link. <br>
                        If you did not receive the email, <a class="highlighted" href="{{ route('resendEmail', ['email' => $email]) }}">click here</a>

                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
