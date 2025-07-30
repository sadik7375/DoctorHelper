<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Prescription;
use App\Models\PrescriptionDrug;
use App\Models\PrescriptionClinicalDiagnosis;
use App\Models\PrescriptionDiagnosisTest;

class PrescriptionController extends Controller
{
   public function store(StorePrescriptionRequest $request)
{
    try {
        DB::beginTransaction();

        $doctorId = auth()->id();

        $prescription = Prescription::create([
            'doctor_id' => $doctorId,
            'patient_id' => $request->patient_id,
            'advice' => $request->advice,
            'next_follow_up_count' => $request->next_follow_up_count,
            'next_follow_up_unit' => $request->next_follow_up_unit,
            'notes' => $request->notes
        ]);

        foreach ($request->clinical_diagnosis_ids as $diagnosisId) {
            PrescriptionClinicalDiagnosis::create([
                'prescription_id' => $prescription->id,
                'clinical_diagnosis_id' => $diagnosisId,
            ]);
        }

        foreach ($request->diagnosis_test_ids as $testId) {
            PrescriptionDiagnosisTest::create([
                'prescription_id' => $prescription->id,
                'diagnosis_test_id' => $testId,
            ]);
        }

        foreach ($request->drugs as $drug) {
            PrescriptionDrug::create([
                'prescription_id' => $prescription->id,
                'drug_id' => $drug['drug_id'],
                'drug_strength_id' => $drug['drug_strength_id'],
                'drug_dose_id' => $drug['drug_dose_id'],
                'drug_duration_id' => $drug['drug_duration_id'],
                'drug_advice_id' => $drug['drug_advice_id'] ?? null,
                'note' => $drug['note'] ?? null,
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Prescription created successfully',
            'data' => $prescription->load(['drugs', 'clinicalDiagnoses', 'diagnosisTests'])
        ], 201);
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Prescription store failed: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong',
            'error' => config('app.debug') ? $e->getMessage() : 'Server error',
        ], 500);
    }
}

    public function show($id)
    {
        $prescription = Prescription::with([
            'drugs.drug',
            'clinicalDiagnoses',
            'diagnosisTests',
            'doctor',
            'patient'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $prescription
        ]);
    }
}
