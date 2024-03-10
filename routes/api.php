<?php
use App\Http\Controllers\ControllerAPISystem;

use Illuminate\Http\Request;
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
Route::post('login', [ControllerAPISystem::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    // list all medication
    Route::get('medication', [ControllerAPISystem::class, 'medication']);
    // get a medication
    Route::get('medication/{id}', [ControllerAPISystem::class, 'singleMedication']);
    // add a new medication
    Route::post('medication', [ControllerAPISystem::class, 'createMedication']);
    // updating a medication
    Route::put('medication/{id}', [ControllerAPISystem::class, 'updateMedication']);
    // updating a medication
    Route::put('medication-status/{id}', [ControllerAPISystem::class, 'softStatusMedication']);
    // delete a medication
    Route::delete('medication/{id}', [ControllerAPISystem::class, 'deleteMedication']);
    // list all users
    Route::get('users', [ControllerAPISystem::class, 'users']);
    // get a user
    Route::get('user/{id}', [ControllerAPISystem::class, 'singleUser']);
    // add a new user
    Route::post('user', [ControllerAPISystem::class, 'createUser']);
    // updating a user
    Route::put('user/{id}', [ControllerAPISystem::class, 'updateUser']);
    // updating a user
    Route::put('user-status/{id}', [ControllerAPISystem::class, 'softStatusUser']);
    // delete a user
    Route::delete('users/{id}', [ControllerAPISystem::class, 'deleteUser']);
});
