<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
      protected $fillable = [
        'doctor_id',
        'name',
        'generic_name',
        'brand_name',
        'details',
    ];
}
