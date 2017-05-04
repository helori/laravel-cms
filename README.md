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
```

Publish and run the migrations:
```bash
php artisan vendor:publish --provider="Helori\LaravelCms\CmsServiceProvider" --tag="migrations"
php artisan migrate
```
