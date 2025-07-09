<?php

namespace App\Http\Controllers\Api\Drug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Drug;

class DrugController extends Controller
{
    public function index()
    {
        $doctorId = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $drugs = Drug::where('doctor_id', $doctorId)->get();
        return response()->json($drugs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        $validated['doctor_id'] = auth()->user()->role === 'staff'
            ? auth()->user()->doctor_id
            : auth()->id();

        $drug = Drug::create($validated);

        return response()->json([
            'message' => 'Drug created successfully',
            'drug' => $drug
        ], 201);
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'generic_name' => 'nullable|string|max:255',
        'brand_name' => 'nullable|string|max:255',
        'details' => 'nullable|string',
    ]);

    $doctorId = auth()->user()->role === 'staff'
        ? auth()->user()->doctor_id
        : auth()->id();

    $drug = Drug::where('id', $id)->where('doctor_id', $doctorId)->firstOrFail();

    $drug->update($request->only([
        'name', 'generic_name', 'brand_name', 'details'
    ]));

    return response()->json([
        'message' => 'Drug updated successfully.',
        'drug' => $drug
    ]);
}
public function destroy($id)
{
    $doctorId = auth()->user()->role === 'staff'
        ? auth()->user()->doctor_id
        : auth()->id();

    $drug = Drug::where('id', $id)->where('doctor_id', $doctorId)->firstOrFail();
    $drug->delete(); // Hard delete if no SoftDeletes trait

    return response()->json([
        'message' => 'Drug permanently deleted.'
    ]);
}

}

