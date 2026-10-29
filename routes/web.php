<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard menggunakan DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route yang membutuhkan user untuk login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Project Routes
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/{project}/export', [ProjectController::class, 'exportCsv'])->name('projects.export');

    // Task Routes
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Habit Routes
    Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
    Route::post('/habits/{habit}/check-in', [HabitController::class, 'checkIn'])->name('habits.checkIn');
    Route::put('/habits/{habit}', [HabitController::class, 'update'])->name('habits.update');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');

    // Notification Routes
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Team & Member Routes
    Route::post('/team/members', [TeamController::class, 'store'])->name('team.members.store');
    Route::delete('/team/members/{member}', [TeamController::class, 'destroy'])->name('team.members.destroy');

    // Admin Routes
    Route::post('/admin/broadcast', [DashboardController::class, 'broadcast'])->name('admin.broadcast');
    Route::delete('/admin/broadcast', [DashboardController::class, 'deleteBroadcast'])->name('admin.broadcast.destroy');
    Route::delete('/admin/users/{user}', [DashboardController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::post('/admin/maintenance', [DashboardController::class, 'toggleMaintenance'])->name('admin.maintenance');
});

// BARIS INI SANGAT PENTING: Untuk memanggil rute /login, /register, dll
require __DIR__.'/auth.php';