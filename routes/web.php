<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/clients', 'index')->name('clients.index');
    Route::get('/clients/create', 'create')->name('clients.create');
    Route::post('/clients', 'store')->name('clients.store');
    Route::get('/clients/{client}', 'show')->name('clients.show');
    Route::get('/clients/{client}/edit', 'edit')->name('clients.edit');
    Route::put('/clients/{client}', 'update')->name('clients.update');
    Route::delete('/clients/{client}', 'destroy')->name('clients.destroy');
});

Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'index')->name('projects.index');
    Route::get('/projects/create', 'create')->name('projects.create');
    Route::post('/projects', 'store')->name('projects.store');
    Route::get('/projects/{project}', 'show')->name('projects.show');
    Route::get('/projects/{project}/edit', 'edit')->name('projects.edit');
    Route::put('/projects/{project}', 'update')->name('projects.update');
    Route::delete('/projects/{project}', 'destroy')->name('projects.destroy');
});

Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'index')->name('tasks.index');
    Route::get('/tasks/create', 'create')->name('tasks.create');
    Route::post('/tasks', 'store')->name('tasks.store');
    Route::get('/tasks/{task}', 'show')->name('tasks.show');
    Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
    Route::put('/tasks/{task}', 'update')->name('tasks.update');
    Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('users.index');
    Route::post('/users', 'store')->name('users.store');
    Route::put('/users/{user}', 'update')->name('users.update');
    Route::delete('/users/{user}', 'destroy')->name('users.destroy');
});

Route::get('/test', [TestController::class, 'index']);
Route::get('add-media-to-library', [TestController::class, 'addMediaToLibrary'])->name('add-media-to-library');

require __DIR__.'/auth.php';
