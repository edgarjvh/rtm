<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;

class MeetingsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        if ($user->organization_owner == 1){
            $events = DB::select(
                "select                
                u.name,
                e.organizer,
                e.title,
                e.start_date,
                e.end_date,
                IFNULL(avg(r.rate),0) as score                 
                from events as e
                left join users as u on e.organizer = u.email
                left join ratings as r on e.event_id = r.event_id
                where u.organization_id = ". $user->organization_id ." and e.event_id is not null
                GROUP BY                 
                e.organizer,
                e.start_date,
                e.end_date,                
                e.title,                
                u.name
                order by start_date desc");

            return collect($events);
        }else{
            return null;
        }
    }
}
