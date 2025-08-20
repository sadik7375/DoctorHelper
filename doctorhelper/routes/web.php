<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::get('/', function () {
    return Inertia::render('Home');
});




Route::get('/login', function () {
    return Inertia::render('Auth/Login');
});


Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});




Route::get('/patients/create', function () {
    return Inertia::render('Patients/Create');
});

Route::get('/patients/all', function () {
    return Inertia::render('Patients/Index');
});


   Route::get('/patients/{id}/edit', function ($id) {
        return Inertia::render('Patients/Edit', ['id' => $id]);
    })->name('patients.edit');