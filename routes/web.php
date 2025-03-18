<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Membership\{Register, BinaryPosition, Payment, BinaryTree};
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Product\Manager as ProductManager;
use App\Livewire\Category\Manager as CategoryManager;
use App\Livewire\Home\Homepage;


Route::get('/', Homepage::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {
    // Membership routes
    Route::get('/membership/register/{sponsor_id?}', Register::class)->name('membership.register');
    Route::get('/membership/position/{membership_id}', BinaryPosition::class)->name('membership.position');
    Route::get('/membership/payment/{membership}', Payment::class)->name('membership.payment');
    Route::get('/membership/tree/{root_id?}', BinaryTree::class)->name('membership.tree');
    Route::get('/member/dashboard', \App\Livewire\Membership\Dashboard::class)->name('member.dashboard');
    Route::get('/membership/select-position/{membership}', BinaryPosition::class)->name('membership.select-position');
});



require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
