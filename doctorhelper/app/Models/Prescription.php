<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'drug_id',
        'drug_strength_id',
        'drug_dose_id',
        'drug_duration_id',
        'drug_advice_id',
        'clinical_diagnosis_id',
        'diagnosis_test_id',
        'advice',
        'follow_up_value',
        'follow_up_unit',
        'next_follow_up',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function strength()
    {
        return $this->belongsTo(DrugStrength::class, 'drug_strength_id');
    }

    public function dose()
    {
        return $this->belongsTo(DrugDose::class, 'drug_dose_id');
    }

    public function duration()
    {
        return $this->belongsTo(DrugDuration::class, 'drug_duration_id');
    }

    public function adviceType()
    {
        return $this->belongsTo(DrugAdvice::class, 'drug_advice_id');
    }

    public function clinicalDiagnosis()
    {
        return $this->belongsTo(ClinicalDiagnosis::class);
    }

    public function diagnosisTest()
    {
        return $this->belongsTo(DiagnosisTest::class);
    }
}
