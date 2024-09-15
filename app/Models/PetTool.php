<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PetTool extends BaseEntity
{
    protected $fillable = ['id', 'toolName', 'type', 'price', 'availableQuantity'];
}

