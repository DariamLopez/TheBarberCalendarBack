<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    /** @use HasFactory<\Database\Factories\PayoutFactory> */
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'period_start',
        'period_end',
        'gross_amount',
        'deductions',
        'net_amount',
        'paid_at',
        'status'
    ];

    //Relations
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    //Scopes
    public function scopeForWorkerBetween($query, $workerId, $startDate, $endDate)
    {
        return $query->where('worker_id', $workerId)
                     ->whereBetween('period_start', [$startDate, $endDate]);
    }
}
