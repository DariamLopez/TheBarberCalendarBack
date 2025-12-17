<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'notes',
        'address',
    ];

    //Relations
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function scopeWithRecentVists($query, $limit = 5)
    {
        return $query->with(['visits' => function ($q) use ($limit) {
            $q->latest()->limit($limit);
        }]);
    }
}
