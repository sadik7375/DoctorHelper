<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDegree extends Model
{
    protected $fillable = ['doctor_id', 'title', 'description'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
