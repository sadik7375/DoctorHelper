<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_uid',
        'doctor_id',
        'name',
        'email',
        'phone_number',
        'age',
        'blood_group',
        'weight',
        'height',
        'gender',
        'marital_status',
        'address'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}

