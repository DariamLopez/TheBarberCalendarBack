<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'default_price',
        'duration_minutes',
        'cost_estimate',
        'is_active'
    ];

    //Relations
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
