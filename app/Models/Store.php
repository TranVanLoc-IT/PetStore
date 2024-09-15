<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends BaseEntity
{
    protected $fillable = ['id', 'serviceName', 'totalCost', 'totalTime'];
}

