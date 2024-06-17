<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'organization_id',
        'meta_id',
        'name',
        'category',
        'language',
        'metadata',
        'status',
        'user_id', // Agrega esto
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
