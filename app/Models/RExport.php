<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RExport extends BaseEntity
{
    use HasFactory;

    protected $fillable = ['dateCreated', 'totalQuantityExport'];
}
