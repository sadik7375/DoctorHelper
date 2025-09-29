<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\AppointmentPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
class AppointmentController extends Controller
{
   public function store(StoreAppointmentRequest $request): JsonResponse
{
    $data = $request->validated();

    try {
        $appointment = DB::transaction(function () use ($data) {
            $appointment = Appointment::create([
                'doctor_id'        => $data['doctor_id'], // ← comes from backend injection
                'patient_id'       => $data['patient_id'],
                'staff_id'         => auth()->id(),
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'status'           => 'confirmed',
                'notes'            => $data['notes'] ?? null,
            ]);

            $fee         = (float) $data['fee'];
            $discount    = (float) ($data['discount'] ?? 0);
            $finalAmount = max(0, $fee - $discount);

            AppointmentPayment::create([
                'appointment_id'  => $appointment->id,
                'fee'             => $fee,
                'discount'        => $discount,
                'discount_reason' => $data['discount_reason'] ?? null,
                'final_amount'    => $finalAmount,
                'payment_method'  => $data['payment_method'],
                'is_paid'         => true,
                'paid_at'         => now(),
            ]);

            return $appointment->load('payment');
        });

        return response()->json([
            'status'      => 'success',
            'message'     => 'Appointment and payment saved successfully.',
            'appointment' => $appointment,
        ], 201);

    } catch (\Throwable $e) {
        Log::error('Appointment create error: '.$e->getMessage());

        return response()->json([
            'status' => 'error',
            'error'  => config('app.debug') ? $e->getMessage() : null,
            'line'   => config('app.debug') ? $e->getLine() : null,
            'file'   => config('app.debug') ? $e->getFile() : null,
        ], 500);
    }
}
    

   public function update(Request $request, $id)
{
    $user = auth()->user();

    $request->validate([
        'appointment_date' => 'required|date',
        'appointment_time' => 'required',
        'fee' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'discount_reason' => 'nullable|string|max:255',
        'payment_method' => 'required|in:cash,card,bkash,rocket,nagad',
        'notes' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $doctorId = $user->role === 'staff' ? $user->doctor_id : $user->id;

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
        ]);

        $discount = $request->discount ?? 0;
        $finalAmount = $request->fee - $discount;

        $appointment->payment->update([
            'fee' => $request->fee,
            'discount' => $discount,
            'discount_reason' => $request->discount_reason,
            'final_amount' => $finalAmount,
            'payment_method' => $request->payment_method,
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment->load('payment')
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Appointment Update Error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update appointment',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}



public function destroy($id)
{
    try {
        $user = auth()->user();
        $doctorId = $user->role === 'staff' ? $user->doctor_id : $user->id;

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $appointment->delete(); // Soft delete

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment deleted successfully'
        ]);
    } catch (\Throwable $e) {
        \Log::error('Appointment Delete Error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete appointment',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:3'
        ]);

        $q = $request->get('phone');

        $patients = Patient::query()
            ->where('phone_number', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id','name','email','phone_number','age','gender']);

        return response()->json([
            'status' => 'success',
            'data'   => $patients
        ]);
    }




}