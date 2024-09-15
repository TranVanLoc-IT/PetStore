<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends BaseEntity
{
    protected $fillable = ['id', 'title', 'totalCost', 'signingDate'];

}


    