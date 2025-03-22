<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Members;
use App\Livewire\Admin\ViewMember;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\Categories;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', Login::class)->name('admin.login');
});

// Admin routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/members', Members::class)->name('admin.members');
    Route::get('/members/{id}',ViewMember::class)->name('admin.members.view');
    Route::post('/verify-member/{id}', [Members::class, 'verifyMember'])->name('admin.verify-member');
    Route::get('/binary-tree', \App\Livewire\Admin\BinaryTree::class)->name('admin.binary-tree');
    Route::get('/manage-positions', \App\Livewire\Admin\ManagePositions::class)->name('admin.manage-positions');
    Route::get('/products', Products::class)->name('admin.products');
    Route::get('/categories', Categories::class)->name('admin.categories');
});

