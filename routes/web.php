<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Membership\{Register, BinaryPosition, Payment, BinaryTree};
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Product\Manager as ProductManager;
use App\Livewire\Category\Manager as CategoryManager;
use App\Livewire\Home\Homepage;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;



Route::get('/', Homepage::class)->name('home');
Route::get('/team', App\Livewire\Home\Team::class)->name('team');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/member/dashboard', \App\Livewire\Member\Dashboard::class)->name('member.dashboard');
    Route::get('/member/profile', \App\Livewire\Member\Profile::class)->name('member.profile');
    Route::get('/member/tree', \App\Livewire\Member\Tree::class)->name('member.tree');
    Route::get('/member/referrals', \App\Livewire\Member\Referrals::class)->name('member.referrals');
    Route::get('/member/binary-position/{member_id}', \App\Livewire\Member\BinaryPosition::class)->name('member.binary-position');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/member/dashboard', \App\Livewire\Member\Dashboard::class)->name('member.dashboard');
    Route::get('/member/profile', \App\Livewire\Member\Profile::class)->name('member.profile');
    Route::get('/member/tree', \App\Livewire\Member\Tree::class)->name('member.tree');
    Route::get('/member/referrals', \App\Livewire\Member\Referrals::class)->name('member.referrals');
    Route::get('/member/select-position/{membership}', \App\Livewire\Member\BinaryPosition::class)->name('member.select-position');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function() {
        if (auth()->user()->membership) {
            return redirect()->route('member.dashboard');
        }
        return redirect()->route('membership.register');
    })->name('dashboard');
});


Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/bihar-shop/public/livewire/update', $handle);
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link has been created!';
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    return "All Caches are cleared by @Roni";
});


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
