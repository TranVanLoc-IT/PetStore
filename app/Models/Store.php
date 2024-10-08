<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends BaseEntity
{
    protected $fillable = ['totalExpense', 'totalRevenue', 'storeName', 'address'];
}

