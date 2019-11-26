<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Organization extends Model
{
    protected $table = 'organizations';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];
}
