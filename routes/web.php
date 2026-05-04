<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [FilesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(IsAdmin::class)->prefix('file')->group(function () {
    Route::get('addFile', [FilesController::class, "shoingForm"]);
    Route::post('insert', [FilesController::class, 'insert']);
});

Route::get('/file/print/{id}', [FilesController::class, 'print']);
Route::get('/file/view/{id}', [FilesController::class, 'view'])
    ->middleware('auth')
    ->name('file.view');
    Route::get('/files/{file}/print', [FilesController::class, 'print'])->name('files.print');
    Route::delete('/file/{id}', [FilesController::class, 'delete'])->name('file.delete');
Route::get('/file/{id}/edit', [FilesController::class, 'edit'])->name('file.edit');
Route::post('/file/{id}/update', [FilesController::class, 'update'])->name('file.update');
require __DIR__.'/auth.php';
