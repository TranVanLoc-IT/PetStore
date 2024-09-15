<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends BaseEntity
{
    protected $fillable = ['id', 'customerName', 'phone', 'customerType'];
}
