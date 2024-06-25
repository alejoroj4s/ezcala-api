<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    protected $fillable = ['organization_id', 'name', 'type']; // Add more field types if needed (e.g., 'textarea', 'select')

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
