<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugStrength extends Model
{
    protected $fillable = [
        'doctor_id',
        'value',
    ];
}
