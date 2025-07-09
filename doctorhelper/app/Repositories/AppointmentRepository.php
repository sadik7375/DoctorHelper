<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function getAppointmentSlipData($id, $doctorId)
    {
        return Appointment::with(['patient', 'payment', 'doctor.degrees'])
            ->where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();
    }
}

