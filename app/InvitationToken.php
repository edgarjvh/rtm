<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitationToken extends Model
{
    protected $table = 'invitation_tokens';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'token', 'user_id', 'partner'
    ];
}