<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Schools\Index as SchoolIndex;
use App\Livewire\Admin\SchoolAdmins\Index as SchoolAdminIndex;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Admin\BookCategories\Index as BookCategoryIndex;
use App\Livewire\Admin\Authors\Index as AuthorIndex;
use App\Livewire\Admin\Publishers\Index as PublisherIndex;
use App\Livewire\Admin\Books\Index as BookIndex;
use App\Livewire\Admin\Subscriptions\Plans\Index as SubscriptionPlanIndex;
use App\Livewire\Admin\Subscriptions\Schools\Index as SchoolSubscriptionIndex;
use App\Livewire\Admin\Subscriptions\Users\Index as UserSubscriptionIndex;
use App\Livewire\Library\Books;
use App\Livewire\Library\BookReader;
use App\Livewire\Admin\Roles\Permissions as RolePermissions;
use App\Livewire\Admin\Users\Permissions as UserPermissions;
use App\Livewire\Admin\AccessControl\Index as AccessControlIndex;




Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');

    // Reader area: all authenticated users
    Route::get('/library', Books::class)->name('library.index');
    Route::get('/library/books/{slug}/read', BookReader::class)->name('library.books.read');

    // Super Admin only
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/schools', SchoolIndex::class)->name('schools.index');
        Route::get('/school-admins', SchoolAdminIndex::class)->name('school-admins.index');

        Route::get('/admin/subscription-plans', SubscriptionPlanIndex::class)
            ->name('admin.subscription-plans.index');

        Route::get('/admin/school-subscriptions', SchoolSubscriptionIndex::class)
            ->name('admin.school-subscriptions.index');

        Route::get('/admin/user-subscriptions', UserSubscriptionIndex::class)
            ->name('admin.user-subscriptions.index');
    });

    // Super Admin + School Admin if they have permission
    Route::middleware(['permission:manage users'])->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
    });

    

    Route::middleware(['permission:manage categories'])->group(function () {
        Route::get('/book-categories', BookCategoryIndex::class)->name('book-categories.index');
    });

    Route::middleware(['permission:manage authors'])->group(function () {
        Route::get('/authors', AuthorIndex::class)->name('authors.index');
    });

    Route::middleware(['permission:manage publishers'])->group(function () {
        Route::get('/publishers', PublisherIndex::class)->name('publishers.index');
    });

    // Books: only users with permission can manage books
    Route::middleware(['permission:manage books'])->group(function () {
        Route::get('/admin/books', BookIndex::class)->name('admin.books.index');
    });
});


Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    Route::get('/admin/role-permissions', RolePermissions::class)
        ->name('admin.role-permissions.index');
});



Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    Route::get('/admin/user-permissions', UserPermissions::class)
        ->name('admin.user-permissions.index');
});

Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    Route::get('/admin/access-control', AccessControlIndex::class)
        ->name('admin.access-control.index');
});


require __DIR__.'/auth.php';