<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'advice' => 'nullable|string',
            'next_follow_up_count' => 'nullable|integer|min:1',
            'next_follow_up_unit' => 'nullable|in:days,weeks,months,years',
            'notes' => 'nullable|string',

            'clinical_diagnosis_ids' => 'required|array',
            'clinical_diagnosis_ids.*' => 'exists:clinical_diagnoses,id',

            'diagnosis_test_ids' => 'required|array',
            'diagnosis_test_ids.*' => 'exists:diagnosis_tests,id',

            'drugs' => 'required|array|min:1',
            'drugs.*.drug_id' => 'required|exists:drugs,id',
            'drugs.*.drug_strength_id' => 'required|exists:drug_strengths,id',
            'drugs.*.drug_dose_id' => 'required|exists:drug_doses,id',
            'drugs.*.drug_duration_id' => 'required|exists:drug_durations,id',
            'drugs.*.drug_advice_id' => 'nullable|exists:drug_advices,id',
            'drugs.*.note' => 'nullable|string'
        ];
    }
}
