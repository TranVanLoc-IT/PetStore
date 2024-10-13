<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends BaseEntity
{
    protected $fillable = ['id', 'moneyRecieved', 'type', 'moneySent', 'timeCreated'];
}

