<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseEntity extends Model
{
    public function ToJsonString():string
    {
        return GetClassName() + json_encode($this->toArray());
    }
    private function GetClassName():string{
        $className = __CLASS__;
        if(str_contains($className, 'R')){
            $className = substr($className, 1, strlen($className));
        }
        return ':'.$className;
    }
}
