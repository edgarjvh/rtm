@extends('layouts.app')

@section('style-home')
    <link rel="stylesheet" href="{{asset('css/meetings.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">

    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('js/meetings.js')}}"></script>
@endsection

@section('content')
    <div class="events-bg"></div>

    <div class="main-container">
        <div class="meeting-score">
            <div class="title">My Meeting Score</div>

            <div class="score">

                <div class="score-value">
                    {{number_format($global_avg, 2)}}
                </div>


                <div class="sharing">
                    <div class="linkedin">
                        <script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script>
                        <script type="IN/Share" data-url="{{env('APP_URL') . '/score/' . number_format($global_avg, 2)}}"></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab tab-my-meetings active" data-name="tab-my-meetings">
                My Meetings
            </div>
            <div class="tab tab-team" data-name="tab-team">
                Team
            </div>
            <div class="tab tab-settings" data-name="tab-settings">
                Settings
            </div>
        </div>

        <div class="tabs-container">
            <div class="tab-content tab-my-meetings active">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Provider</th>
                            <th scope="col">Organizer</th>
                            <th scope="col">Title</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Rate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($newEvents) > 0)
                            @foreach($newEvents as $event)
                                <tr>
                                    <td class="align-middle"><img
                                                src="{{'/img/'.strtolower($event->provider) . '.png'}}"
                                                style="width:16px; margin-top:-5px"
                                                alt=""> {{ ucwords($event->provider) }}</td>
                                    <td class="align-middle">
                                        <b>{{$event->name}}</b>
                                        <br>
                                        <small>{{$event->organizer}}</small>
                                    </td>
                                    <td class="align-middle">  {{$event->title}}</td>
                                    <td class="align-middle">{{(new DateTime($event->start_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</td>
                                    <td class="align-middle">{{(new DateTime($event->end_date,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($tz))->format('Y-m-d H:i:s')}}</td>
                                    <td class="align-middle">{{$event->rate === null ? 'unrated' : number_format($event->rate,1)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">No meetings to show</td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            {{--<td colspan="6">{{ $newEvents->links() }}</td>--}}
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="tab-content tab-team">
                Tab Team
            </div>

            <div class="tab-content tab-settings">
                Tab Settings
            </div>
        </div>
    </div>

@endsection