<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RActiveOn extends BaseEntity
{
    use HasFactory;

    protected $fillable = ['type', 'dateStart', 'dateEnd', 'condition'];
}
