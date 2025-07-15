<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalDiagnosis extends Model
{
      protected $fillable = ['doctor_id', 'name', 'details'];
}
