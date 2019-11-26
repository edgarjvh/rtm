@extends('layouts.app')

@section('content')
    <div class="events-bg"></div>
    <div class="container pt-4 pb-4 h-100">
        <div class="col justify-content-center p-0 d-flex flex-column h-100">
            <div class="card border-0 w-100 flex-grow-1">
                <div class="card-header bg-transparent border-0 d-flex flex-row justify-content-between"
                     style="font-family: 'Merriweather', serif;color: #C96756;font-size: 1rem">

                    <div>
                        My Meetings
                    </div>

                    <img src="{{Request::root() . '/img/icon.png'}}" style="width: 32px;" alt="">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" style="font-family: Source Code Pro, sans-serif">
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
                                    <td class="align-middle">{{ ucwords($event->provider) }}</td>
                                    <td class="align-middle">
                                        <b>{{$event->name}}</b>
                                        <br>
                                        <small>{{$event->organizer}}</small>
                                    </td>
                                    <td class="align-middle">{{$event->title}}</td>
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
                            <td colspan="6">{{ $newEvents->links() }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection