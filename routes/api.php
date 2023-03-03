<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\leaveTypes;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/search', [UserController::class, 'search']);
    Route::post('/update/{id}', [AuthController::class, 'update']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/request', [LeaveRequestController::class, 'index']);
    Route::post('/requests', [LeaveRequestController::class, 'store']);
    Route::put('/request/{id}', [LeaveRequestController::class, 'update']);
//    Route::get('/leave_types', [leaveTypes::class, 'index']);
//    Route::post('/leave_types', [leaveTypes::class, 'store']);
//    Route::put('/leave_type/{id}', [leaveTypes::class, 'update']);
    // Route::delete('/leave-types/{id}', [leaveTypes::class, 'destroy']);
//       Route::apiResource('leave-types', '\App\Http\Controllers\leaveTypes');
    Route::put('/users/{id}/salary',  [UserController::class, 'updateSalary'])->name('users.update_salary');;





});

//Public routes

Route::get('/', function (Request $request) {
    return "hello world!";
});
Route::post('/login', [AuthController::class, 'login'])->name('login');


