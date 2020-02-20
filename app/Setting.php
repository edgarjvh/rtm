<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'sendind_rating_emails', 'sendind_rating_emails_not_hosted', 'sharing_meeting_score'
    ];
}
