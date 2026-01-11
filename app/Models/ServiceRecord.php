<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRocordFactory> */
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'service_id',
        'worker_id',
        'price',
        'cost',
        'commission_amount',
        'commission_rate',
        'status',
        'notes'
    ];

    //Relations
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    //Scopes
    public function doneBetweenDates($query, $startDate, $endDate)
    {
        return $query->where('status', 'done')
                     ->whereBetween('created_at', [$startDate, $endDate]);
    }

    //Snapshot and commision calculation on creating/updating
    protected static function booted()
    {
        static::creating(function (self $serviceRecord) {
            if (empty($serviceRecord->price) && $serviceRecord->service_id) {
                $service = Service::find($serviceRecord->service_id);
                if ($service) {
                    $serviceRecord->price = $service->default_price;
                    $serviceRecord->cost = $service->cost_estimate;
                }
            }
            if (is_null($serviceRecord->commission_rate)){
                if($serviceRecord->worker_id){
                    $worker = Worker::find($serviceRecord->worker_id);
                    $serviceRecord->commission_rate = $worker ? $worker->commission_rate : 0;
                }
                else{
                    $serviceRecord->commission_rate = 0;
                }
            }
            $serviceRecord->commission_amount = $serviceRecord->calculateCommissionAmount();
        });
        static::created(function (self $serviceRecord) {
            if ($serviceRecord->visit)
                $serviceRecord->visit->recalculateTotals();
        });
        static::updated(function (self $serviceRecord) {
            $serviceRecord->commission_amount = $serviceRecord->calculateCommissionAmount();
            if ($serviceRecord->visit)
                $serviceRecord->visit->recalculateTotals();
        });
        static::deleted(function (self $serviceRecord) {
            if ($serviceRecord->visit)
                $serviceRecord->visit->recalculateTotals();
        });
    }
    public Function calculateCommissionAmount(): float
    {
        if (is_null($this->commission_rate) || $this->commission_rate <= 0) {
            return 0;
        }
        return round(($this->price * $this->commission_rate) / 100, 2);
    }
}
