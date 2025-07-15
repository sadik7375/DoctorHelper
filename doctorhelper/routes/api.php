<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorProfileController;
use App\Http\Controllers\Api\Drug\DrugController;
use App\Http\Controllers\Api\Drug\DrugStrengthController;
use App\Http\Controllers\Api\Drug\DrugDoseController;
use App\Http\Controllers\Api\Drug\DrugDurationController;
use App\Http\Controllers\Api\Drug\DrugAdviceController;
use App\Http\Controllers\Api\ClinicalDiagnosisController;
use App\Http\Controllers\Api\DiagnosisTestController;
use App\Http\Controllers\Api\PrescriptionController;


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
    Route::get('/printslip/{id}', [AppointmentController::class, 'printSlipJson']);

//------------------------Doctor Profile---------------------------------------

Route::post('/doctor-profile', [DoctorProfileController::class, 'store']); 
Route::get('/doctor-profile', [DoctorProfileController::class, 'show']);

//-----------------------Drug management---------------------------------------
Route::get('/drugs', [DrugController::class, 'index']);
Route::post('/drugs', [DrugController::class, 'store']);
Route::put('/drugs/{id}', [DrugController::class, 'update']);
Route::delete('/drugs/{id}', [DrugController::class, 'destroy']);

//------------------------------Drug strength-----------------------------------------


Route::get('/drug-strengths', [DrugStrengthController::class, 'index']);
Route::post('/drug-strengths', [DrugStrengthController::class, 'store']);
Route::put('/drug-strengths/{id}', [DrugStrengthController::class, 'update']);
Route::delete('/drug-strengths/{id}', [DrugStrengthController::class, 'destroy']);


//--------------------------------Drug Dose----------------------------------------------

Route::get('/drug-doses', [DrugDoseController::class, 'index']);
Route::post('/drug-doses', [DrugDoseController::class, 'store']);
Route::put('/drug-doses/{id}', [DrugDoseController::class, 'update']);
Route::delete('/drug-doses/{id}', [DrugDoseController::class, 'destroy']);


//------------------------------Drug Duration-------------------------------------------



Route::get('/drug-durations', [DrugDurationController::class, 'index']);
Route::post('/drug-durations', [DrugDurationController::class, 'store']);
Route::put('/drug-durations/{id}', [DrugDurationController::class, 'update']);
Route::delete('/drug-durations/{id}', [DrugDurationController::class, 'destroy']);

//-----------------------------Drug Advice-----------------------------------------------



Route::get('/drug-advices', [DrugAdviceController::class, 'index']);
Route::post('/drug-advices', [DrugAdviceController::class, 'store']);
Route::put('/drug-advices/{id}', [DrugAdviceController::class, 'update']);
Route::delete('/drug-advices/{id}', [DrugAdviceController::class, 'destroy']);

//----------------------------Clinical diagnoses-----------------------------------------



Route::get('/clinical-diagnoses', [ClinicalDiagnosisController::class, 'index']);
Route::post('/clinical-diagnoses', [ClinicalDiagnosisController::class, 'store']);
Route::put('/clinical-diagnoses/{id}', [ClinicalDiagnosisController::class, 'update']);
Route::delete('/clinical-diagnoses/{id}', [ClinicalDiagnosisController::class, 'destroy']);


//-----------------------------Diagnosis Tests----------------------------------------------




Route::get('/diagnosis-tests', [DiagnosisTestController::class, 'index']);
Route::post('/diagnosis-tests', [DiagnosisTestController::class, 'store']);
Route::put('/diagnosis-tests/{id}', [DiagnosisTestController::class, 'update']);
Route::delete('/diagnosis-tests/{id}', [DiagnosisTestController::class, 'destroy']);


//-------------------------------Prescription-------------------------------------------------

Route::post('/prescriptions', [PrescriptionController::class, 'store']);



});




Route::get('/', function () {
    return view('welcome');
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




