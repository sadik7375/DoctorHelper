<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionClinicalDiagnosis extends Model
{
   protected $fillable = [
        'prescription_id',
        'clinical_diagnosis_id'
    ];
}
