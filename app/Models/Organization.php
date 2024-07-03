<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;


class Organization extends Model
{

    use HasFactory;
    
    protected $fillable = ['name'];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'relation_user_organization', 'organization_id', 'user_id');
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'relation_user_organization');
    }
    
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function lists()
    {
        return $this->hasMany(ContactList::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function whatsappAccounts()
    {
        return $this->hasMany(WhatsAppAccount::class);
    }
}
