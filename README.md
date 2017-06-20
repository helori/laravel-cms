# laravel-cms
Work still in progress...
This package provides many things you usually need when creating a website for your clients : a secured admin panel with users management, an advanced media manager to upload and optimize files (images, videos, documents...), and a powerful tool to define your website's specific elements : blog articles, home page elements, partners, portfolio... This package also provides a way to include structured data in your website. The only thing you will have to do is using these components in your front controller, and build your design. And if you need, a pre-configured AMP compatible layout is also available.

## Installation and setup

On a fresh Laravel (>= v5.4) installation, install the package by running:
```bash
composer require helori/laravel-cms
```

Configure your application:
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
```php
// app/Exceptions/Handler.php
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
$admin->email = "admin@domain.com"
$admin->password = bcrypt('admin_password')
$admin->save()
exit
```

Publish the laravel-cms default assets and Vue components:
```bash
php artisan vendor:publish --tag=laravel-cms-assets
```

At the time of this writing, Laravel 5.4 ships with the following dependencies pre-configured in your package.json:
```json
"devDependencies": {
    "axios": "^0.15.3",
    "bootstrap-sass": "^3.3.7",
    "cross-env": "^3.2.3",
    "jquery": "^3.1.1",
    "laravel-mix": "0.*",
    "lodash": "^4.17.4",
    "vue": "^2.1.10"
}
```

Install these dependencies by running: 
```bash
npm install
```

Install additionnal dependencies required by this package:
```bash
npm install font-awesome --save-dev
npm install tinymce --save-dev
```

Edit your laravel mix config file :
```js
// webpack.mix.js
const { mix } = require('laravel-mix');
mix.copy(
    "./node_modules/font-awesome/fonts",
    "./public/fonts"
).sass(
    "./resources/assets/sass/admin.scss",
    "./public/css/admin.css"
).js(
    "./resources/assets/js/admin.js",
    "./public/js/admin.js", "."
).sass(
    "./resources/assets/sass/website.scss",
    "./public/css/website.css"
).js(
    "./resources/assets/js/website.js",
    "./public/js/website.js", "."
).version();
```

Compile your assets :
```bash
npm run dev
```

If you want to modify the default views, publish them to your application's resources directory:
```bash
php artisan vendor:publish --tag=laravel-cms-views
```

You can also publish the translations to overwrite de defaults:
```bash
php artisan vendor:publish --tag=laravel-cms-translations
```

## Usage


