<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugAdvice extends Model
{

    protected $table = 'drug_advices';
    protected $fillable = ['doctor_id', 'value'];
}
