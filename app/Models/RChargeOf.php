<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RChargeOf extends BaseEntity
{
    use HasFactory;

    protected $fillable = [ "totalTimeWorked", "lastUpdatedTime"];
}