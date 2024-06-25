<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'created_by', 'organization_id', 'list_id', 'template_id', 'variables', 'scheduled_at', 'send_now'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function list()
    {
        return $this->belongsTo(ContactList::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
