<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RPaySalary extends BaseEntity
{
    use HasFactory;

    protected $fillable = ['date', 'value'];
}
