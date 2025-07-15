<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicalDiagnosis;
use Illuminate\Http\Request;

class ClinicalDiagnosisController extends Controller
{
    private function getDoctorId(): int
    {
        $user = auth()->user();
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }

    public function index()
    {
        return ClinicalDiagnosis::where('doctor_id', $this->getDoctorId())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $diagnosis = ClinicalDiagnosis::create([
            'doctor_id' => $this->getDoctorId(),
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json(['message' => 'Diagnosis created', 'data' => $diagnosis], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $diagnosis = ClinicalDiagnosis::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $diagnosis->update([
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json(['message' => 'Diagnosis updated', 'data' => $diagnosis]);
    }

    public function destroy($id)
    {
        $diagnosis = ClinicalDiagnosis::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $diagnosis->delete();

        return response()->json(['message' => 'Diagnosis deleted']);
    }
}
