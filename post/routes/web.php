<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notification', function () {
    return view('notification');
})->middleware(['auth', 'verified'])->name('notification');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/like', [PostController::class, 'likePost']);
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment']);

    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('loadNotifications');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('unreadNotifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
});

require __DIR__.'/auth.php';
