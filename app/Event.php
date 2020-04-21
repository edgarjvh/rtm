<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $table = 'events';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'organizer','start_date','end_date','attendees','event_id','provider','title','description'
    ];

}
