<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentPayment extends Model
{
    protected $fillable = [
        'appointment_id', 'fee', 'discount', 'discount_reason',
        'final_amount', 'payment_method', 'is_paid', 'paid_at'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
