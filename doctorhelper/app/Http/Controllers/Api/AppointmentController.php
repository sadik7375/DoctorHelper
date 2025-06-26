<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentPayment;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'fee' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,card,bkash,rocket,nagad',
        ]);

        try {
            DB::beginTransaction();

            $doctorId = auth()->user()->role === 'staff'
                ? auth()->user()->doctor_id
                : auth()->id();

            $appointment = Appointment::create([
                'doctor_id' => $doctorId,
                'patient_id' => $request->patient_id,
                'staff_id' => auth()->id(),
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'status' => 'confirmed',
                'notes' => $request->notes,
            ]);

            $discount = $request->discount ?? 0;
            $finalAmount = $request->fee - $discount;

            AppointmentPayment::create([
                'appointment_id' => $appointment->id,
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
                'message' => 'Appointment and payment saved successfully.',
                'appointment' => $appointment->load('payment'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
