<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['user_id', 'organization_id', 'name', 'phone', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function lists()
    {
        return $this->belongsToMany(ContactList::class, 'relation_contact_list');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'relation_contact_tag');
    }
}
