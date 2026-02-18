<?php

use App\Http\Controllers\Calendar;
use App\Http\Controllers\Event;
use App\Http\Controllers\Completed;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Notes;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landing;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeeklyScheduleController;

// Landing
Route::get('/', function () {
    return view('landing.index');
});
Route::get('/landing', [Landing::class, 'index']);
Route::get('/dashboard', [Dashboard::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('board', [BoardController::class, 'index']);

// Auth
Route::get('/register', [AuthController::class, 'index'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('auth/google', [App\Http\Controllers\AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);

// Event Calendar
Route::middleware(['auth'])->group(function () {
    Route::get('/event', [EventController::class, 'index'])->name('calendar');
    Route::get('/events', [EventController::class, 'getEvents']);
    Route::get('/events/{event}', [EventController::class, 'show']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);
});

// Notes
Route::middleware(['auth'])->group(function () {
    Route::get('/notes', [Notes::class, 'index'])->name('notes.index');
    Route::post('/notes', [Notes::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}', [Notes::class, 'show'])->name('notes.show');
    Route::put('/notes/{id}', [Notes::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [Notes::class, 'destroy'])->name('notes.destroy');
});

// Boards Tasks
Route::middleware(['auth'])->group(function () {
    // Boards
    Route::get('/boards', [App\Http\Controllers\BoardController::class, 'index'])->name('boards.index');
    Route::get('/boards/{boardId}', [App\Http\Controllers\BoardController::class, 'show'])->name('boards.show');
    Route::post('/boards', [App\Http\Controllers\BoardController::class, 'store'])->name('boards.store');
    Route::put('/boards/{boardId}', [App\Http\Controllers\BoardController::class, 'update'])->name('boards.update');
    Route::delete('/boards/{boardId}', [App\Http\Controllers\BoardController::class, 'destroy'])->name('boards.destroy');

    // Tasks
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
});


Route::middleware(['auth'])->group(function () {
    // Weekly Schedule routes
    Route::get('/weekly-schedule', [WeeklyScheduleController::class, 'index'])->name('weekly-schedule.index');
    Route::post('/weekly-schedule', [WeeklyScheduleController::class, 'store'])->name('weekly-schedule.store');
    Route::get('/weekly-schedule/{weeklySchedule}/edit', [WeeklyScheduleController::class, 'edit'])->name('weekly-schedule.edit');
    Route::patch('/weekly-schedule/{schedule}/update-field', [WeeklyScheduleController::class, 'updateField']);
    Route::delete('/weekly-schedule/{weeklySchedule}', [WeeklyScheduleController::class, 'destroy'])->name('weekly-schedule.destroy');
    Route::put('/weekly-schedule/{weeklySchedule}', [WeeklyScheduleController::class, 'update'])
    ->name('weekly-schedule.update');

    // Notifications
    Route::post('/notifications/mark-all-read', function () {
        \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
});

