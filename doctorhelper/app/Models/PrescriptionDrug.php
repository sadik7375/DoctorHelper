<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDrug extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'drug_id',
        'drug_strength_id',
        'drug_dose_id',
        'drug_duration_id',
        'advice_id',
        'note'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
