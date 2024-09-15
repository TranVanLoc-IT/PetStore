<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends BaseEntity
{
    protected $fillable = ['vendorName', 'address', 'phone'];
}
