<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
     return redirect('task');
});

Route::resource('project', ProjectController::class);
Route::resource('task', TaskController::class);
Route::post('view', [TaskController::class, 'view'])->name('view');
Route::post('/task-sortable', [TaskController::class, 'sortable']);
