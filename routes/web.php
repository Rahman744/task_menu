<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;

Route::get('/', [TaskController::class, 'index'])->name('home');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/lists', [TaskListController::class, 'store'])->name('lists.store');
Route::get('/lists/{id}', [TaskController::class, 'filterByList'])->name('tasks.filterByList');
Route::delete('/lists/{id}', [TaskListController::class, 'destroy'])->name('lists.destroy');
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::get('/tasks/{task}', [TaskController::class, 'show']);
Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

