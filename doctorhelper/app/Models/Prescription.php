<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'advice_id',
        'next_follow_up_count',
        'next_follow_up_unit',
        'notes',
    ];

    public function drugs()
    {
        return $this->hasMany(PrescriptionDrug::class);
    }

    public function clinicalDiagnoses()
    {
        return $this->belongsToMany(
            ClinicalDiagnosis::class,
            'prescription_clinical_diagnoses' // ✅ FIXED HERE
        );
    }

    public function diagnosisTests()
    {
        return $this->belongsToMany(
            DiagnosisTest::class,
            'prescription_diagnosis_tests'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function advice()
    {
        return $this->belongsTo(DrugAdvice::class, 'advice_id');
    }
}
