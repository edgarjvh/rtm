@extends('layouts.app')

@section('style-invite-team')
    <link rel="stylesheet" href="{{asset('css/invite-your-team.css')}}">
    <script src="{{asset('js/invite-your-team.js')}}"></script>
@endsection

@section('content')
    <div class="main-container">
        @if(isset($sent) && isset($alreadyRegistered) && isset($invalidEmail))
            <div class="msg">
                <div class="sent">{{$sent}} invitations sent</div>
                <div class="msg-sep">-</div>
                <div class="not-sent">{{$alreadyRegistered}} already registered</div>
                <div class="msg-sep">-</div>
                <div class="not-sent">{{$invalidEmail}} invalid email</div>
            </div>
        @endif

        <div class="inv-options">
            <div class="opt col">
                <div class="title">
                    Invite your team
                </div>
                <div class="subtitle">
                    Rate This Meeting works better as a team!
                </div>
                <img src="img/invite-team.png" alt="">
            </div>

            <div class="opt">
                <form method="POST" action="{{ route('invite-your-team') }}" autocomplete="off">
                    @csrf

                    <div class="partners-container">
                        <label for="partner1" class="m-0">Email Addresses (comma separated)</label>

                        <div class="form-group">
                            <textarea id="partner1" type="email"
                                   class="form-control partner" name="partners">
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group submit">
                        <button type="submit" class="nav-link">
                            SEND INVITATIONS
                        </button>
                    </div>

                    <div class="form-group continue-btn">
                        <a href="/home" class="nav-link">
                            Or continue and send later
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection