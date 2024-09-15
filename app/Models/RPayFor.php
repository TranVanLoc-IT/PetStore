<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayFor extends BaseModel
{
    protected $fillable = ['dateCreated', 'status'];
}

