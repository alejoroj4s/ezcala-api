<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','organization_id', 'name', 'type'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}