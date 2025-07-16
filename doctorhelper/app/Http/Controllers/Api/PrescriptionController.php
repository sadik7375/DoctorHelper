<?php

namespace App\Http\Controllers\Api;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Prescription;
use App\Models\PrescriptionDrug;
use App\Models\PrescriptionClinicalDiagnosis;
use App\Models\PrescriptionDiagnosisTest;

class PrescriptionController extends Controller
{
    public function store(Request $request)
    {
       try {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'advice_id' => 'nullable|exists:drug_advices,id',
            'next_follow_up_count' => 'nullable|integer|min:1',
            'next_follow_up_unit' => 'nullable|in:days,weeks,months,years',
            'notes' => 'nullable|string',

            'clinical_diagnosis_ids' => 'required|array',
            'clinical_diagnosis_ids.*' => 'exists:clinical_diagnoses,id',

            'diagnosis_test_ids' => 'required|array',
            'diagnosis_test_ids.*' => 'exists:diagnosis_tests,id',

            'drugs' => 'required|array',
            'drugs.*.drug_id' => 'required|exists:drugs,id',
            'drugs.*.drug_strength_id' => 'required|exists:drug_strengths,id',
            'drugs.*.drug_dose_id' => 'required|exists:drug_doses,id',
            'drugs.*.drug_duration_id' => 'required|exists:drug_durations,id',
            'drugs.*.drug_advice_id' => 'nullable|exists:drug_advices,id',
            'drugs.*.note' => 'nullable|string'
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'validation_error',
            'errors' => $e->errors()
        ], 422);
    }
        try {
            DB::beginTransaction();

            $doctorId = auth()->id();

            $prescription = Prescription::create([
                'doctor_id' => $doctorId,
                'patient_id' => $request->patient_id,
                'advice_id' => $request->advice_id,
                'next_follow_up_count' => $request->next_follow_up_count,
                'next_follow_up_unit' => $request->next_follow_up_unit,
                'notes' => $request->notes
            ]);

            // Save Clinical Diagnoses
            foreach ($request->clinical_diagnosis_ids as $diagnosisId) {
                PrescriptionClinicalDiagnosis::create([
                    'prescription_id' => $prescription->id,
                    'clinical_diagnosis_id' => $diagnosisId,
                ]);
            }

            // Save Diagnosis Tests
            foreach ($request->diagnosis_test_ids as $testId) {
                PrescriptionDiagnosisTest::create([
                    'prescription_id' => $prescription->id,
                    'diagnosis_test_id' => $testId,
                ]);
            }

            // Save Drug Variations
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
            'advice',
            'doctor',
            'patient'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $prescription
        ]);
    }
}

