<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">
    <title>Document</title>

    <style>
        .score-ranking-container {
            width: 100%;
            max-width: 1024px;
            margin: 0 auto;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .score-ranking-container .ranking-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .score-ranking-container .ranking-title span.title {
            font-family: 'Merriweather', 'serif';
            font-weight: bold;
            font-style: italic;
            color: #CC6666;
            font-size: 20px;
        }

        .score-ranking-container .ranking-title img {
            width: 80px;
        }

        .score-ranking-container .ranking-table {
            display: flex;
            flex-direction: column;
            font-family: 'Source Sans Pro', 'sans-serif';
        }

        .score-ranking-container .ranking-table .hidden {
            display: none;
        }

        .score-ranking-container .ranking-table .thead,
        .score-ranking-container .ranking-table .tbody,
        .score-ranking-container .ranking-table .tbody-wrapper {
            display: flex;
            flex-direction: column;
        }

        .score-ranking-container .ranking-table .trow {
            display: flex;
            align-items: center;
        }

        .score-ranking-container .ranking-table .trow .tcol.avatar {
            max-width: 70px;
            min-width: 70px;
        }

        .score-ranking-container .ranking-table .trow .tcol.avatar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 2px;
        }

        .score-ranking-container .ranking-table .trow .tcol.user-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.7);
            padding-left: 15px !important;
        }

        .score-ranking-container .ranking-table .trow .tcol.user-info .user-name {
            font-weight: bold;
        }

        .score-ranking-container .ranking-table .trow .tcol.rate {
            margin-left: 10px;
        }

        .score-ranking-container .ranking-table .tbody .trow {
            border-top: 1px solid rgba(0, 0, 0, 0.2);
            transition: ease 0.3s;
        }

        .score-ranking-container .ranking-table .tbody .trow .tcol {
            padding: 20px 5px;
        }

        .score-ranking-container .ranking-table .tbody .trow .tcol.rate {
            font-family: 'Merriweather', 'serif';
            font-weight: bold;
            font-style: italic;
            color: #CC6666;
            font-size: 24px !important;
        }

        .score-ranking-container .ranking-table .tbody .trow:last-child {
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        }

        .score-ranking-container .ranking-table .tbody .trow:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }
    </style>
</head>

<body>
<div class="score-ranking-container">
    <div class="ranking-title">
      <span class="title">
        Meeting Score Top Rating
      </span>


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
                @foreach($rankingList as $rank)
                    <div class="trow">
                        <div class="tcol user-id hidden">{{$rank->id}}</div>
                        <div class="tcol avatar">
                            @if($rank->linkedin_avatar)
                                <img src="{{$rank->linkedin_avatar}}" alt="">
                            @elseif($rank->google_avatar)
                                <img src="{{$rank->google_avatar}}" alt="">
                            @else
                                <img src="{{asset('img/default-profile.png')}}" alt="">
                            @endif
                        </div>
                        <div class="tcol user-info">
                            <span class="user-name">{{$rank->name}}</span>
                            <span class="job-title">
                                {{$rank->organization_owner == 1 ? 'President and Owner' : 'Member'}}
                            </span>
                            <span class="organization-name">{{$rank->organization_name}}</span>
                        </div>
                        <div class="tcol rate">{{number_format($rank->score, 2)}}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>

</html>