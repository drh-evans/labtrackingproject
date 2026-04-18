<?php

use App\Http\Controllers\ChatroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabTaskController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleImportController;
use App\Http\Controllers\EnrolmentImportController;
use App\Http\Controllers\UserImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // check if a user is logged in, if not they get the welcome page
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::patch('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

    Route::get('/modules/{module}/chatroom', [ChatroomController::class, 'show'])->name('chatroom.show');
});

Route::middleware(['auth','can:access-admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/import/modules', [ModuleImportController::class,'import'])->name('modules.import');
    Route::post('/admin/import/enrolment', [EnrolmentImportController::class,'import'])->name('enrolment.import');
    Route::post('/admin/import/users', [UserImportController::class,'import'])->name('users.import');
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

Route::middleware(['can:accessQueue,module'])->group(function () {
    Route::get('/modules/{module}/queue', [QueueController::class, 'show'])->name('module.queue');
    Route::post('/modules/{module}/queue/join', [QueueController::class, 'joinQueue'])->name('queue.join');
});



Route::middleware(['can:signOff,module'])->group(function () {
    Route::get('/modules/{module}/staff/students', [ModuleController::class, 'search'])->name('module.search');
    Route::get('/modules/{module}/staff/students/student/{student}', [ModuleController::class, 'signOff'])->name('module.signoff');
});

require __DIR__.'/auth.php';
