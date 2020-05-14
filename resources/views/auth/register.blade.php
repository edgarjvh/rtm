@extends('layouts.app')

@section('style-register')
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection

@section('content')
    <div class="reg-container">
        <div class="title">
            <span>Start now!</span>
        </div>

        <div class="reg-options">
            <form method="post" action="{{route('join')}}">
                @csrf
                <input type="hidden" name="clicked" value="clicked">
                <button type="submit" class="opt">
                    <img src="img/reg-opt-1.png" alt="">

                    <p>
                        <span>Do you want to <span class="highlighted">join</span> an existing organization?</span>
                    </p>

                    <div class="bottom-line"></div>
                </button>
            </form>

            <form method="post" action="{{route('create')}}">
                @csrf
                <input type="hidden" name="clicked" value="clicked">
                <button type="submit" class="opt">
                    <img src="img/reg-opt-2.png" alt="">

                    <p>
                        <span>Do you want to <span class="highlighted">create</span> your own organization?</span>
                    </p>

                    <div class="bottom-line"></div>
                </button>
            </form>
        </div>
    </div>


@endsection
