<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseEntity extends Model
{
    public function toJsonString()
    {
        return json_encode($this->toArray());
    }
}
