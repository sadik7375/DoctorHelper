<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugDose extends Model
{
     protected $fillable = ['doctor_id', 'value'];
}
