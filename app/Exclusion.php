<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exclusion extends Model
{
    protected $table = 'exclusions';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_email', 'email'
    ];
}
