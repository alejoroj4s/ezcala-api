<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    protected $table = 'lists';
    
    protected $fillable = ['user_id', 'organization_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'relation_contact_list', 'list_id', 'contact_id');
    }
}
