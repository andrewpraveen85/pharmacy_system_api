<?php
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AuthendicController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [AuthendicController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    // list all medication
    Route::get('medications', [SystemController::class, 'medications']);
    // get a medication
    Route::get('medication/{id}', [SystemController::class, 'singleMedication']);
    // add a new medication
    Route::post('medication', [SystemController::class, 'createMedication']);
    // updating a medication
    Route::put('medication/{id}', [SystemController::class, 'updateMedication']);
    // updating a medication
    Route::put('medication-status/{id}', [SystemController::class, 'softStatusMedication']);
    // delete a medication
    Route::delete('medication/{id}', [SystemController::class, 'deleteMedication']);
    // list all customer
    Route::get('customers', [SystemController::class, 'customers']);
    // get a medication
    Route::get('customer/{id}', [SystemController::class, 'singleCustomer']);
    // add a new medication
    Route::post('customer', [SystemController::class, 'createCustomer']);
    // updating a medication
    Route::put('customer/{id}', [SystemController::class, 'updateCustomer']);
    // updating a medication
    Route::put('customer-status/{id}', [SystemController::class, 'softStatusCustomer']);
    // delete a medication
    Route::delete('customer/{id}', [SystemController::class, 'deleteCustomer']);
    // list all users
    Route::get('users', [SystemController::class, 'users']);
    // get a user
    Route::get('user/{id}', [SystemController::class, 'singleUser']);
    // add a new user
    Route::post('user', [SystemController::class, 'createUser']);
    // updating a user
    Route::put('user/{id}', [SystemController::class, 'updateUser']);
    // updating a user
    Route::put('user-status/{id}', [SystemController::class, 'softStatusUser']);
    // delete a user
    Route::delete('users/{id}', [SystemController::class, 'deleteUser']);
    // logout a user
    Route::post('logout', [SystemController::class, 'logout']);
});
