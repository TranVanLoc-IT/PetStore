<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RImport extends BaseEntity
{
    use HasFactory;

    protected $fillable = ['dateCreated', 'totalQuantityImport'];
}
