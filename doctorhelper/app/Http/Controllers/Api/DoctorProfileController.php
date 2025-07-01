<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorDegree;
use Illuminate\Support\Facades\DB;

class DoctorProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'degrees' => 'required|array|min:1',
            'degrees.*.title' => 'required|string',
            'degrees.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $doctor = Doctor::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            foreach ($request->degrees as $degree) {
                $doctor->degrees()->create([
                    'title' => $degree['title'],
                    'description' => $degree['description'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'doctor' => $doctor->load('degrees')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show()
    {
        $doctor = Doctor::where('user_id', auth()->id())->with('degrees')->first();

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 404);
        }

        return response()->json($doctor);
    }
}

