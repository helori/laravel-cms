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
php artisan vendor:publish --tag=laravel-cms-migrations
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

Add routes for the admin panel:
// routes/web.pbp
```php
// Login, logout and welcome views :
Helori\LaravelCms\CmsServiceProvider::routes('admin');

Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function ()
{
    Helori\LaravelCms\CmsServiceProvider::routes('crudui');
});
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
```

Edit your laravel mix config file :
// webpack.mix.js
```js
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

Compile your assets running one of these commands (see Laravel mix docs for more information):
```bash
npm run prod // for production
npm run dev // for development
npm run watch // when developping
```

If you want to modify the default views, publish them to your application's resources directory:
```bash
php artisan vendor:publish --tag=laravel-cms-views
```

You can also publish the translations to overwrite de defaults:
```bash
php artisan vendor:publish --tag=laravel-cms-translations
```

