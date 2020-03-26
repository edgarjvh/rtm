<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherMeeting extends Model
{
    protected $table = 'other_meetings';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'host_email',
        'attendees_email',
        'host_linkedin'
    ];
}
