<?php

namespace App\Models;

use App\Enums\SalaryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceRecord;

class Worker extends Model
{
    /** @use HasFactory<\Database\Factories\WorkerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'role',
        'salary_type',
        'salary_amount',
        'commission_rate',
        'is_active',
        'service_category_id'
    ];

    /* protected $casts = [
        'salary_type' => SalaryType::class,
    ]; */


    //Relations
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
    //Helpers
    public function getEffectiveCommissionRate(): float
    {
        return $this->commission_rate ?? 0.0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
