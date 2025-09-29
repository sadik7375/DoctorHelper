<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $user = auth()->user();

        // staff → use their assigned doctor_id; doctor → use own id
        $doctorId = $user->role === 'staff' ? $user->doctor_id : $user->id;

        // inject so rules can validate it (client doesn’t have to send it)
        $this->merge([
            'doctor_id' => $doctorId,
        ]);
    }

    public function rules(): array
    {
        return [
            'doctor_id'        => 'required|exists:users,id', // now satisfied by prepareForValidation()
            'patient_id'       => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required', // or: 'required|date_format:H:i'
            'fee'              => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0',
            'discount_reason'  => 'nullable|string|max:255',
            'payment_method'   => 'required|in:cash,card,bkash,rocket,nagad',
            'notes'            => 'nullable|string',
        ];
    }
}
