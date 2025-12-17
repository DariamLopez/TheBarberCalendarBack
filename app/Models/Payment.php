<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'method',
        'amount',
        'tip_amount',
        'notes',
    ];

    //Relations
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    //Scopes
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }

    // Model events: update visit paid_amount/status and audit logs
    protected static function booted()
    {
        static::creating(function (self $payment) {
            if (empty($payment->paid_at)) {
                $payment->paid_at = Carbon::now();
            }
        });
        static::created(function (self $payment) {
            if ($payment->visit)
                $payment->visit->recalculateTotals();
        });

        static::updated(function ($payment) {
            if ($payment->visit)
                $payment->visit->recalculateTotals();
        });

        static::deleted(function ($payment) {
            if ($payment->visit)
                $payment->visit->recalculateTotals();
        });
    }
}
