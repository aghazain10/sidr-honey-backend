<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'index']);
    Route::get('/admin/messages', [\App\Http\Controllers\MessageController::class, 'index'])
        ->name('admin.messages.index');

    Route::resource('admin/blogs', \App\Http\Controllers\Admin\BlogController::class)
        ->names('admin.blogs')
        ->parameters(['blogs' => 'blog']);
    Route::post('admin/blogs/upload-image', [\App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])
        ->name('admin.blogs.upload-image');
});


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

require __DIR__ . '/auth.php';
