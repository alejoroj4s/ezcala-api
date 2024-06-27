<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function whatsappAccounts()
    {
        return $this->hasMany(WhatsAppAccount::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'relation_user_organization');
    }
}