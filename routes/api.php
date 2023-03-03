<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
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
    //Auth
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //user
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/search', [UserController::class, 'search']);
       Route::post('user_delete', [UserController::class, 'destroy'])->name('user_delete');
       Route::post('user_salary', [UserController::class, 'updateSalary'])->name('user_salary');



    //leaverequest
    Route::get('/request', [LeaveRequestController::class, 'index']);
    Route::post('/requests', [LeaveRequestController::class, 'store']);
    Route::post('/request/update', [LeaveRequestController::class, 'update']);

    //leavetype
    Route::get('/leave_types', [LeaveTypeController::class, 'index']);
    Route::post('/leave_types', [LeaveTypeController::class, 'store']);
    Route::delete('/leave_types', [LeaveTypeController::class, 'destroy']);








});

//Public routes

Route::get('/', function (Request $request) {
    return "hello world!";
});
Route::post('/login', [AuthController::class, 'login'])->name('login');


