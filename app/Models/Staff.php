<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends BaseEntity
{
    protected $fillable = ['staffId', 'staffName', 'phone', 'seniority', 'yearIn', 'active'];

}

