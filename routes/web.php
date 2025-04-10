<?php

use App\Http\Controllers\Completed;
use App\Http\Controllers\Personal;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Work;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landing;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Home;

// Landing
Route::get('/', function () {
    return view('landing.index');
});
Route::get('/landing', [Landing::class, 'index']);
Route::get('/home', [Home::class, 'index'])->middleware('auth')->name('home');
Route::get('/personal', [Personal::class, 'index']);
Route::get('/work', [Work::class, 'index']);
Route::get('/completed', [Completed::class, 'index']);

// Auth
Route::get('/register', [AuthController::class, 'index'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/toggle-complete', [TaskController::class, 'toggleComplete']);
    Route::get('/completed', [TaskController::class, 'completed'])->name('tasks.completed');
});
