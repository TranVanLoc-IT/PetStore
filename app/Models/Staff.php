<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends BaseEntity
{
    protected $fillable = ['id', 'staffName', 'phone', 'seniority', 'yearIn', 'role'];

}

