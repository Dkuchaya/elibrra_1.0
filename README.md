# eLibrary Laravel + Livewire Starter

This starter prepares the rebuild of the old CodeIgniter eLibrary into Laravel + Livewire, while keeping the backend API-ready for a future Vue frontend.

## Install

```bash
laravel new elibrary
cd elibrary
composer require livewire/livewire spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
```

Copy the files from this package into your Laravel project, then run:

```bash
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Default roles

- Super Admin
- School Admin
- Librarian/Admin
- Individual User/Student
- Reader

## Data migration

Import your old SQL dump into a separate database, for example `old_elibrary`, then configure `.env`:

```env
OLD_DB_DATABASE=old_elibrary
OLD_DB_USERNAME=root
OLD_DB_PASSWORD=
```

Then run:

```bash
php artisan elibrary:migrate-old-data
```
