<?php

use App\Http\Controllers\LabTaskController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::patch('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
});

Route::middleware(['can:access-admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['can:access-enrolment'])->group(function () {
    Route::get('/modules/{module}/enrolment', [ModuleController::class, 'enrolment'])->name('module.enrolment');
});

Route::middleware('auth')->group(function () {
    Route::get('/modules/{module}/lab-tasks/create', [LabTaskController::class, 'create'])->name('lab-task.create');
    Route::post('/modules/{module}/lab-tasks', [LabTaskController::class, 'store'])->name('lab-task.store');
    Route::patch('/modules/{module}/lab-tasks/{labTask}/edit', [LabTaskController::class, 'update'])->name('lab-task.update');
    Route::delete('/modules/{module}/lab-tasks/{labTask}', [LabTaskController::class, 'destroy'])->name('lab-task.destroy');
});

Route::middleware(['can:signOff,module'])->group(function () {
    Route::get('/modules/{module}/staff/students', [ModuleController::class, 'search'])->name('module.search');
    Route::get('/modules/{module}/staff/students/student/{student}', [ModuleController::class, 'signOff'])->name('module.signoff');
    
});

require __DIR__.'/auth.php';
