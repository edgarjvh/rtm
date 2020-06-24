@extends('layouts.app')

@section('style-ranking')
    <link rel="stylesheet" href="{{asset('css/score-ranking.css')}}">
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('js/score-ranking.js')}}"></script>
@endsection

@section('content')
    <div class="score-ranking-container">
        <div class="ranking-title">
            <span class="title">
                Meeting Score Top Rating
            </span>

            {{--<img src="{{asset('img/linkedin2.png')}}" alt="">--}}
        </div>

        <div class="ranking-table">
            <div class="thead">
                <div class="trow">
                    <div class="tcol avatar"></div>
                    <div class="tcol user-info"></div>
                    <div class="tcol rate">Meeting Score</div>
                </div>
            </div>

            <div class="tbody">
                <div class="tbody-wrapper">
                    @foreach($ranking as $user)
                        <div class="trow">
                            <div class="tcol user-id hidden">{{$user->id}}</div>
                            <div class="tcol avatar">
                                @if($user->linkedin_avatar)
                                    <img src="{{$user->linkedin_avatar}}" alt="">
                                @elseif($user->google_avatar)
                                    <img src="{{$user->google_avatar}}" alt="">
                                @else
                                    <img src="{{asset('img/default-profile.png')}}" alt="">
                                @endif
                            </div>
                            <div class="tcol user-info">
                                <span class="user-name">{{$user->name}}</span>
                                <span class="job-title">
                                    {{$user->organization_owner == 1 ? 'President and Owner' : 'Member'}}
                                </span>
                                <span class="organization-name">{{$user->organization_name}}</span>
                            </div>
                            <div class="tcol rate">{{number_format($user->score, 2)}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection