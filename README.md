# laravel-cms
This package provides an admin panel with a built-in media library and the ability to define and administrate everything you need in your website. , a blog manager and a page manager. You can also create custom collections for your content. Each collection is a table in your database : you can define its fields directly from the admin panel, and then create elements for your collection. Typically, a collection can be a gallery, a list of partners, a list of clients...

## Installation and setup

On a fresh Laravel (>= v5.4) installation, install the package by running:
```bash
composer require helori/laravel-cms
```

Configure your application (Laravel version < 5.5):
```php
// config/app.php
'providers' => [
    ...
    Helori\LaravelCms\CmsServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
    Laravel\Scout\ScoutServiceProvider::class,
];

'aliases' => [
    ...
    'Image' => Intervention\Image\Facades\Image::class,
];
```
Setup the guard, provider and password reset options to handle administrator authentication :
```php
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
],
'passwords' => [
    ...
    'admins' => [
        'provider' => 'admins',
        'table' => 'admins_resets',
        'expire' => 60,
    ],
],
```
Configure redirection if an auth exception is raised :
```php
// app/Exceptions/Handler.php
use Illuminate\Auth\AuthenticationException;
...
protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    $guard = array_get($exception->guards(), 0);
    if($guard === 'admin'){
        return redirect()->guest(route('admin-login'));
    }else{
        return redirect()->guest(route('login'));
    }
}
```
Configure redirection if an administrator is already authenticated :
```php
// app/Middleware/RedirectIfAuthenticated.php
public function handle($request, Closure $next, $guard = null)
{
    if (Auth::guard($guard)->check()) {
        if($guard === 'admin'){
            return redirect()->route('admin-home');
        }else{
            return redirect('/');
        }
    }

    return $next($request);
}
```

Run the migrations:
```bash
php artisan migrate
```

Create the first administrator to be able to connect the first time:
```bash
php artisan tinker
$admin = new \Helori\LaravelCms\Models\Admin
$admin->name = 'John'
$admin->email = 'admin@domain.com'
$admin->password = bcrypt('admin_password')
$admin->save()
exit
```

Publish the laravel-cms default assets and Vue components:
```bash
php artisan vendor:publish --tag=laravel-cms-assets
php artisan vendor:publish --tag=laravel-cms-components
```

Install the default laravel npm dependencies (to run mix)
```bash
npm install
```

Install the package's font-end dependencies: 
```bash
npm install axios@0.* bootstrap-sass@3.* jquery@3.* lodash@4.* vue@2.* vuex@2.* vue-router@2.* font-awesome tinymce moment vue-crud --save-dev
```

Edit your laravel mix config file :
```js
// webpack.mix.js
mix.copy(
    "./node_modules/font-awesome/fonts",
    "./public/fonts"
).sass(
    "./resources/assets/sass/admin.scss",
    "./public/css/admin.css"
).sass(
    "./resources/assets/sass/tinymce.scss",
    "./public/css/tinymce.css"
).js(
    "./resources/assets/js/admin.js",
    "./public/js/admin.js", "."
);
```

Compile your assets :
```bash
npm run dev
```

Your administrator panel should now available:
```bash
http://your-website.dev/admin
```

## Usage


