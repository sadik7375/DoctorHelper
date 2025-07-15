<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'clinical_diagnosis_id' => 'required|exists:clinical_diagnoses,id',
        'diagnosis_test_id' => 'required|exists:diagnosis_tests,id',
        'advice_id' => 'required|exists:drug_advices,id',
        'next_follow_up_count' => 'nullable|integer|min:1',
        'next_follow_up_unit' => 'nullable|in:days,weeks,months,years',
        'drug_id' => 'required|exists:drugs,id',
        'drug_dose_id' => 'required|exists:drug_doses,id',
        'drug_strength_id' => 'required|exists:drug_strengths,id',
        'drug_duration_id' => 'required|exists:drug_durations,id',
    ]);

    try {
        DB::beginTransaction();

        $doctorId = auth()->user()->id;

        $prescription = Prescription::create([
            'doctor_id' => $doctorId,
            'patient_id' => $request->patient_id,
            'clinical_diagnosis_id' => $request->clinical_diagnosis_id,
            'diagnosis_test_id' => $request->diagnosis_test_id,
            'advice_id' => $request->advice_id,
            'next_follow_up_count' => $request->next_follow_up_count,
            'next_follow_up_unit' => $request->next_follow_up_unit,
            'drug_id' => $request->drug_id,
            'drug_dose_id' => $request->drug_dose_id,
            'drug_strength_id' => $request->drug_strength_id,
            'drug_duration_id' => $request->drug_duration_id,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Prescription created successfully',
            'data' => $prescription
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Prescription Store Error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create prescription',
            'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            'line' => config('app.debug') ? $e->getLine() : null,
            'file' => config('app.debug') ? $e->getFile() : null
        ], 500);
    }
}

}
