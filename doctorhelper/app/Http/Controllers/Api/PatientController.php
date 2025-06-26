<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * List all patients for the authenticated doctor or staff.
     */
    public function index()
    {
        $doctorId = $this->getDoctorId();

        $patients = Patient::where('doctor_id', $doctorId)->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $patients,
        ], 200);
    }

    /**
     * Store a new patient with unique email & phone per doctor.
     */
    public function store(Request $request)
    {
        $doctorId = $this->getDoctorId();

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => [
                'nullable',
                'email',
                Rule::unique('patients')->where(function ($query) use ($doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
            ],
            'phone_number'   => [
                'required',
                'string',
                Rule::unique('patients')->where(function ($query) use ($doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
            ],
            'age'            => 'required|integer|min:0|max:150',
            'blood_group'    => 'nullable|string|max:5',
            'weight'         => 'nullable|numeric|min:0|max:500',
            'height'         => 'nullable|numeric|min:0|max:300',
            'gender'         => 'required|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'address'        => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $patient = Patient::create([
            ...$validator->validated(),
            'doctor_id'   => $doctorId,
            'patient_uid' => 'PAT-' . strtoupper(uniqid()),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Patient created successfully.',
            'data' => $patient,
        ], 201);
    }

    public function update(Request $request, $id)
{
    $doctorId = $this->getDoctorId();

    $patient = Patient::where('id', $id)->where('doctor_id', $doctorId)->first();

    if (! $patient) {
        return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name'           => 'sometimes|string|max:255',
        'email'          => [
            'nullable',
            'email',
            Rule::unique('patients')->ignore($patient->id)->where(function ($q) use ($doctorId) {
                return $q->where('doctor_id', $doctorId);
            })
        ],
        'phone_number'   => [
            'sometimes',
            Rule::unique('patients')->ignore($patient->id)->where(function ($q) use ($doctorId) {
                return $q->where('doctor_id', $doctorId);
            })
        ],
        'age'            => 'sometimes|integer|min:0|max:150',
        'blood_group'    => 'nullable|string|max:5',
        'weight'         => 'nullable|numeric|min:0|max:500',
        'height'         => 'nullable|numeric|min:0|max:300',
        'gender'         => 'sometimes|in:male,female,other',
        'marital_status' => 'nullable|in:single,married,divorced,widowed',
        'address'        => 'nullable|string|max:500',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'validation_error', 'errors' => $validator->errors()], 422);
    }

    $patient->update($validator->validated());

    return response()->json(['status' => 'success', 'message' => 'Patient updated successfully.', 'data' => $patient]);
}

public function destroy($id)
{
    $doctorId = $this->getDoctorId();

    $patient = Patient::where('id', $id)->where('doctor_id', $doctorId)->first();

    if (! $patient) {
        return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
    }

    $patient->delete(); // Soft delete

    return response()->json(['status' => 'success', 'message' => 'Patient deleted successfully.']);
}

    /**
     * Get the actual doctor_id whether logged in user is doctor or staff.
     */
    private function getDoctorId(): int
    {
        $user = auth()->user();
        return $user->role === 'staff' ? $user->doctor_id : $user->id;
    }
}

