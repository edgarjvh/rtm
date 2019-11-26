<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingKey extends Model
{
    protected $table = 'rating_keys';
    public $primaryKey = 'id';
    public $timestamps = true;
}
