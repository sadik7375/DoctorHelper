<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorProfileController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    //-----------------------user authentication----------------------------
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);


  //-------------------------Patients----------------------------------------

    Route::get('/patients', [PatientController::class, 'index']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::put('/patients/{id}', [PatientController::class, 'update']);
    Route::delete('/patients/{id}', [PatientController::class, 'destroy']);


 //-------------------------Appointment---------------------------------------

   Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

//------------------------Doctor Profile---------------------------------------

Route::post('/doctor-profile', [DoctorProfileController::class, 'store']); 
 Route::get('/doctor-profile', [DoctorProfileController::class, 'show']);


});




Route::get('/', function () {
    return view('welcome');
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
