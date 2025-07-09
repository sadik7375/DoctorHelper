<?php

namespace App\Http\Controllers\Api\Drug;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugStrength;

class DrugStrengthController extends Controller
{
    public function index()
    {
        $doctorId = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $strengths = DrugStrength::where('doctor_id', $doctorId)->get();

        return response()->json($strengths);
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255'
        ]);

        $doctorId = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $strength = DrugStrength::create([
            'doctor_id' => $doctorId,
            'value' => $request->value,
        ]);

        return response()->json([
            'message' => 'Drug strength created successfully',
            'data' => $strength
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255'
        ]);

        $doctorId = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $strength = DrugStrength::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $strength->update(['value' => $request->value]);

        return response()->json([
            'message' => 'Drug strength updated successfully',
            'data' => $strength
        ]);
    }

    public function destroy($id)
    {
        $doctorId = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $strength = DrugStrength::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $strength->delete();

        return response()->json(['message' => 'Drug strength deleted successfully']);
    }
}

