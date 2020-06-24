<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'organization_owner',
        'verify_token',
        'email_verified_at',
        
        'google_account',
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_expiry_token',
        'google_avatar',
        
        'linkedin_account',
        'linkedin_id',
        'linkedin_access_token',
        'linkedin_refresh_token',
        'linkedin_expiry_token',
        'linkedin_avatar',
        
        'outlook_account',
        'outlook_id',
        'outlook_access_token',
        'outlook_refresh_token',
        'outlook_expiry_token',
        'outlook_avatar',

        'timezone',
        'has_invited',
        'avatar',
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
