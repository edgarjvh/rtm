<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherRanking extends Model
{
    protected $table = 'other_rankings';
    public $primaryKey = 'id';
    public $timestamps = true;
}
