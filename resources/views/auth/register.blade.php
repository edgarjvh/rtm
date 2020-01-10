@extends('layouts.app')

@section('style-register')
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection

@section('content')
    {{--<div class="banner"></div>--}}

    <div class="reg-container">
        <div class="title">
            Start now!
        </div>

        <div class="reg-options">
            <a class="opt" href="#">
                <img src="img/reg-opt-1.png" alt="">

                <p>
                    Do you want to <span class="highlighted">join</span> an existing organization?
                </p>

                <div class="bottom-line"></div>
            </a>

            <a class="opt" href="#">
                <img src="img/reg-opt-2.png" alt="">

                <p>
                    Do you want to <span class="highlighted">create</span> your own organization?
                </p>

                <div class="bottom-line"></div>
            </a>

        </div>
    </div>


@endsection
