<?php

namespace App\Http\Controllers\Api\Drug;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugDose;


class DrugDoseController extends Controller
{
    private function getDoctorId(): int
    {
        $user = auth()->user();
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }

    public function index()
    {
        return DrugDose::where('doctor_id', $this->getDoctorId())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $dose = DrugDose::create([
            'doctor_id' => $this->getDoctorId(),
            'value' => $request->value,
        ]);

        return response()->json(['message' => 'Drug dose created', 'data' => $dose], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $dose = DrugDose::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $dose->update(['value' => $request->value]);

        return response()->json(['message' => 'Drug dose updated', 'data' => $dose]);
    }

    public function destroy($id)
    {
        $dose = DrugDose::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $dose->delete();

        return response()->json(['message' => 'Drug dose deleted']);
    }
}
