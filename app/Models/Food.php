<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends BaseEntity
{
    protected $fillable = ['foodId', 'foodName', 'price', 'availableQuantity'];
}

