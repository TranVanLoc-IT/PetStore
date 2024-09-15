<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCreatedBy extends BaseEntity
{
    use HasFactory;
    protected $fillable = ['dateCreated', 'totalValue'];
}
