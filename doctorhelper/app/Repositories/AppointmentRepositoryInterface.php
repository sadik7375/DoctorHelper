<?php

namespace App\Repositories;

interface AppointmentRepositoryInterface
{
     public function getAppointmentSlipData($id, $doctorId);
}
