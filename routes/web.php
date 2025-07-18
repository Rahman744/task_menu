<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('home');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');




