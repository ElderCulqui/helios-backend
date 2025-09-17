<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::get('departments/{department}/subdepartments', [DepartmentController::class, 'getSubdepartments'])->name('departments.get-subdepartments');
Route::resource('departments', DepartmentController::class)->names('department');
