<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'address'];

    public function degrees()
    {
        return $this->hasMany(DoctorDegree::class);
    }
}
