<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id', 'patient_id', 'staff_id', 'appointment_date',
        'appointment_time', 'status', 'notes'
    ];

    public function payment()
    {
        return $this->hasOne(AppointmentPayment::class);
    }
}

