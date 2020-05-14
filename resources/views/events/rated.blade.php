@extends('layouts.app')

@section('style-rated')
    <link rel="stylesheet" href="{{asset('css/rated.css')}}">
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('js/rated.js')}}"></script>
@endsection

@section('content')
    @csrf
    <div class="container flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1 justify-content-center align-items-center p-4">
            <div class="col-md-4" style="margin-top: -100px">
                <div class="col">
                    <div style="font-family: Merriweather, Serif; font-size: 30px;color: #C96756;text-align: center;margin-bottom: 15px;">
                        Thank you
                    </div>

                    <p style="font-family: 'Source Sans Pro',sans-serif; font-size: 1.1rem; text-align: center"
                       class="mt-2">
                        Your rating has been placed!
                    </p>

                    <textarea id="rating-comment" title="Comments" placeholder="Any comments?"></textarea>

                    <div class="submit-container">
                        <div class="submit-btn">
                            SUBMIT
                        </div>
                    </div>

                    <p style="font-family: 'Source Sans Pro',sans-serif; font-size: 1.1rem; text-align: center"
                       class="mt-2">
                        if you would like to rate your own meetings <a href="/register">click here</a> to sign up.
                    </p>

                    <input type="hidden" value="{{$event_id}}" id="event_id">
                    <input type="hidden" value="{{$rating_key}}" id="rating_key">
                </div>
            </div>
        </div>
    </div>
@endsection