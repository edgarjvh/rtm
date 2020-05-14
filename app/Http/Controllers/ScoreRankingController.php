<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreRankingController extends Controller
{
    public function showRanking(){

        $rankingList = DB::select(
            "(select
            avg(r.rate) as score,
            u.id,
            u.name,
            u.organization_id,
            u.organization_owner,
            o.name as 'organization_name',
            u.linkedin_avatar,
            u.google_avatar
            from users as u
            left join events as e on u.email = e.organizer
            left join ratings as r on e.event_id = r.event_id
            left join organizations as o on u.organization_id = o.id
            where u.email != '".env('RATE_THEIR_MEETING_ACCOUNT')."'
            and u.token_host is null
            group by 
            u.email,
            u.id,
            u.name,
            u.organization_id,
            u.organization_owner,
            o.name,
            u.linkedin_avatar,
            u.google_avatar
            )
            union
            (
            select
            t.rate as score,
            t.id,
            trim(concat(t.first_name, ' ', ifnull(t.last_name, ''))) as name,
            concat(0) as organization_id,
            concat('') as organization_owner,
            concat('') as organization_name,
            concat('') as linkedin_avatar,
            concat('') as google_avatar
            from other_rankings as t
            )
            ORDER BY score desc, name asc"
        );

        return view('score-ranking')->with(['ranking' => $rankingList]);
    }
}
