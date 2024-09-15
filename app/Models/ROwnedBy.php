<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnedBy extends BaseModel
{
    protected $fillable = ['confirmStatus', 'productSupplyQuantity'];
}
