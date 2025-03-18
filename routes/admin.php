<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', Login::class)->name('admin.login');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('admin.dashboard');
});
