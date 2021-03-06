<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(array $array)
 */
class Exclusion extends Model
{
    protected $table = 'exclusions';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_email', 'email'
    ];
}
