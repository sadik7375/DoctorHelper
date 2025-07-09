<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugDuration extends Model
{
    protected $fillable = ['doctor_id', 'value'];
}
