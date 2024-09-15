<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends BaseEntity
{
    protected $fillable = ['id', 'petName', 'price', 'description', 'size', 'remainingStock'];
}

