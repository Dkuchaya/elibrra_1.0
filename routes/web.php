<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Schools\Index as SchoolIndex;


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

require __DIR__.'/auth.php';
