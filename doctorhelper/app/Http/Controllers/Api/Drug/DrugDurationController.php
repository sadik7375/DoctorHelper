<?php

namespace App\Http\Controllers\Api\Drug;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugDuration;

class DrugDurationController extends Controller
{
    private function getDoctorId(): int
    {
        $user = auth()->user();
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }

    public function index()
    {
        return DrugDuration::where('doctor_id', $this->getDoctorId())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $duration = DrugDuration::create([
            'doctor_id' => $this->getDoctorId(),
            'value' => $request->value,
        ]);

        return response()->json(['message' => 'Drug duration created', 'data' => $duration], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $duration = DrugDuration::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $duration->update(['value' => $request->value]);

        return response()->json(['message' => 'Drug duration updated', 'data' => $duration]);
    }

    public function destroy($id)
    {
        $duration = DrugDuration::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $duration->delete();

        return response()->json(['message' => 'Drug duration deleted']);
    }
}
