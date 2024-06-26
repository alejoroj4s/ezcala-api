<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['user_id', 'organization_id', 'name', 'phone', 'email'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function lists()
    {
        return $this->belongsToMany(ContactList::class, 'relation_contact_list', 'contact_id', 'list_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'relation_contact_tag', 'contact_id', 'tag_id');
    }

    public function customFieldValues()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
