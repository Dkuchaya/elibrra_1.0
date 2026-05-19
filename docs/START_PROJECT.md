# Start Project Checklist

## 1. Create Laravel project

```bash
laravel new elibrary
cd elibrary
```

Choose:
- Breeze or Livewire starter kit
- Blade + Livewire
- MySQL

## 2. Install required packages

```bash
composer require livewire/livewire spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
```

## 3. Copy starter files

Copy all files in this package into the Laravel project root.

## 4. Register middleware

In Laravel 11/12, add aliases in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
    ]);
})
```

## 5. Register command

For Laravel 11/12, commands in `app/Console/Commands` are auto-discovered. If not, register `MigrateOldElibraryData` in your Console Kernel.

## 6. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=SuperAdminSeeder
```

## 7. Old database migration

Import `mtecnpjn_elib_test.sql` into a separate MySQL database, for example:

```txt
old_elibrary
```

Add the old database connection snippet from `config/database_old_connection_snippet.php` into Laravel `config/database.php`.

Add this to `.env`:

```env
OLD_DB_HOST=127.0.0.1
OLD_DB_PORT=3306
OLD_DB_DATABASE=old_elibrary
OLD_DB_USERNAME=root
OLD_DB_PASSWORD=
```

Then run:

```bash
php artisan elibrary:migrate-old-data --default-password=password123
```

## 8. Next build modules

1. Authentication and force password change
2. Super Admin dashboard
3. School management
4. User management
5. Book management
6. Author, publisher, category management
7. Subscription management
8. Reader PDF viewer
9. Reports and activity logs
10. API endpoints for future Vue frontend
