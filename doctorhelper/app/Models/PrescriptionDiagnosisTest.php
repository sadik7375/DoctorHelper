<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDiagnosisTest extends Model
{
   protected $fillable = [
        'prescription_id',
        'diagnosis_test_id'
    ];
}
