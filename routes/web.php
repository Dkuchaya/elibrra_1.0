<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Schools\Index as SchoolIndex;
use App\Livewire\Admin\SchoolAdmins\Index as SchoolAdminIndex;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Admin\BookCategories\Index as BookCategoryIndex;
use App\Livewire\Admin\Authors\Index as AuthorIndex;
use App\Livewire\Admin\Publishers\Index as PublisherIndex;




Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware([
    'auth',
])->group(function () {

    Route::get('/dashboard', function () {

        return view('dashboard');

    })->name('dashboard');

});


Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/schools', SchoolIndex::class)->name('schools.index');
});


Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/school-admins', SchoolAdminIndex::class)
        ->name('school-admins.index');
});


Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
});


Route::middleware(['auth', 'permission:manage categories'])->group(function () {
    Route::get('/book-categories', BookCategoryIndex::class)
        ->name('book-categories.index');
});


Route::middleware(['auth', 'permission:manage authors'])->group(function () {
    Route::get('/authors', AuthorIndex::class)->name('authors.index');
});


Route::middleware(['auth', 'permission:manage publishers'])->group(function () {

    Route::get('/publishers', PublisherIndex::class)
        ->name('publishers.index');

});

require __DIR__.'/auth.php';
