<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends BaseEntity
{
    protected $fillable = ['invoiceId', 'dateCreated', 'totalCost', 'totalAmount'];
}


