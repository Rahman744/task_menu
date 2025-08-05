<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TagController;

Route::get('/', [TaskController::class, 'index'])->name('home');

Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

Route::post('/lists', [TaskListController::class, 'store'])->name('lists.store');
Route::get('/lists/{id}', [TaskController::class, 'filterByList'])->name('tasks.filterByList');
Route::delete('/lists/{id}', [TaskListController::class, 'destroy'])->name('lists.destroy');

Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
