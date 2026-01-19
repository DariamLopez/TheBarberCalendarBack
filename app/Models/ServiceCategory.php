<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    //Relations
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
