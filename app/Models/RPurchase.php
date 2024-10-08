<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RPurchase extends BaseEntity
{
    use HasFactory;
    protected $fillable = ['price', 'quantity'];
}
