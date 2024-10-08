<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class PetTool extends BaseEntity
{
    protected $fillable = ['toolId', 'toolName', 'type', 'price', 'availableQuantity'];
}

