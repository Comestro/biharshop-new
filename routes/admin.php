<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Members;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', Login::class)->name('admin.login');
});

// Admin routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/members', Members::class)->name('admin.members');
    Route::post('/verify-member/{id}', [Members::class, 'verifyMember'])->name('admin.verify-member');
    Route::get('/binary-tree', \App\Livewire\Admin\BinaryTree::class)->name('admin.binary-tree');
});

