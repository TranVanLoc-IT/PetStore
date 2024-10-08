<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends BaseEntity
{
    protected $fillable = ['contractId', 'title', 'totalCost', 'signingDate', 'description', 'sellerName', 'phone'];
    
}


    