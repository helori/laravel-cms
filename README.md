# laravel-cms
An easy to use CMS for creating Laravel websites

## Installation and setup

```bash
composer require helori/laravel-cms
```

Configure your application:
```php
// config/app.php
'providers' => [
    ...
    Helori\LaravelCms\CmsServiceProvider::class,
];
// config/auth.php
'guards' => [
    ...
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],
'providers' => [
    ...
    'admins' => [
        'driver' => 'eloquent',
        'model' => Helori\LaravelCms\Models\Admin::class,
    ]
]

// app/Http/Kernel.php
protected $routeMiddleware = [
    ...
    'auth.admin' => \Helori\LaravelCms\Middlewares\AdminMiddleware::class,
];
```

Publish and run the migrations:
```bash
php artisan vendor:publish --provider="Helori\LaravelCms\CmsServiceProvider" --tag="migrations"
php artisan migrate
```

Create the first administrator to be able to connect the first time:
```bash
php artisan tinker
$admin = new \Helori\LaravelCms\Models\Admin
$admin->email = "admin_email@domain.com"
$admin->password = bcrypt('admin_password')
$admin->save()
exit
```