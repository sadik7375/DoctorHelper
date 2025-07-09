<?php

namespace App\Http\Controllers\Api\Drug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugAdvice;

class DrugAdviceController extends Controller
{
    private function getDoctorId(): int
    {
        $user = auth()->user();
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }

    public function index()
    {
        return DrugAdvice::where('doctor_id', $this->getDoctorId())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $advice = DrugAdvice::create([
            'doctor_id' => $this->getDoctorId(),
            'value' => $request->value,
        ]);

        return response()->json(['message' => 'Drug advice created', 'data' => $advice], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $advice = DrugAdvice::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $advice->update(['value' => $request->value]);

        return response()->json(['message' => 'Drug advice updated', 'data' => $advice]);
    }

    public function destroy($id)
    {
        $advice = DrugAdvice::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $advice->delete();

        return response()->json(['message' => 'Drug advice deleted']);
    }
}

