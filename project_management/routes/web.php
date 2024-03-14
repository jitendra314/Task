<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'create']);
Route::delete('/projects/{id}', [ProjectController::class, 'delete']);
Route::get('/projects/{id}', [ProjectController::class, 'getProjectDetails']);
Route::put('/projects/{id}', [ProjectController::class, 'updateProject']);
