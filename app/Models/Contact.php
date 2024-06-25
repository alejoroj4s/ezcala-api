<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['created_by', 'organization_id', 'name', 'phone', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function customFieldValues()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
