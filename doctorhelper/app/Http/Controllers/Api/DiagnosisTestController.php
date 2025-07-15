<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisTest;
use Illuminate\Http\Request;

class DiagnosisTestController extends Controller
{
    private function getDoctorId(): int
    {
        $user = auth()->user();
        if (!$user) abort(401, 'Unauthorized');
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }

    public function index()
    {
        $tests = DiagnosisTest::where('doctor_id', $this->getDoctorId())->get();
        return response()->json($tests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $test = DiagnosisTest::create([
            'doctor_id' => $this->getDoctorId(),
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json(['message' => 'Test created', 'data' => $test], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $test = DiagnosisTest::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $test->update([
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json(['message' => 'Test updated', 'data' => $test]);
    }

    public function destroy($id)
    {
        $test = DiagnosisTest::where('id', $id)
            ->where('doctor_id', $this->getDoctorId())
            ->firstOrFail();

        $test->delete();

        return response()->json(['message' => 'Test deleted']);
    }
}

