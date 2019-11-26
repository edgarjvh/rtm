<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    public $primaryKey = 'id';
    public $timestamps = true;
}
