<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
        'tax',
        'discount',
        'notes',
    ];

    //Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    //Scopes
    public function scopeForDate($query, $date)
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();
        return $query->whereBetween('created_at', [$start, $end]);
    }

    public function recalculateTotals(): void
    {
        $subtotal = $this->serviceRecords()->where('status', '!=', 'cancelled')->sum('price');
        $tip = (float) $this->payments()->sum('tip_amount');
        $paid = (float) $this->payments()->sum('amount');

        $tax = $this->tax ?? 0;
        $discount = $this->discount ?? 0;

        $total = $subtotal + $tax - $discount;

        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->amount_paid = $paid + $tip;
        $this->payment_status = $this->determinePaymentStatus();
        $this->save();
    }

    protected function determinePaymentStatus(): string
    {
        if ($this->paid_amount <= 0) {
            return 'unpaid';
        }
        else if ($this->paid_amount < $this->total) {
            return 'partial';
        }
        return 'paid';
    }
}
