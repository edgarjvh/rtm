<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = 'recipients';
    public $primaryKey = 'id';
    public $timestamps = true;
}
