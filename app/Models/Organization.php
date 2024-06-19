<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name'];

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
}
