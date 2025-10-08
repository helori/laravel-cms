# laravel-cms

## Installation and setup

laravel new my-project
cd my-project

### Install Laravel Santum (API)

```console
php artisan install:api
php artisan config:publish cors
```

Modify your .env file using your project name as follows :

```
SESSION_DOMAIN=".my-project.test"
SANCTUM_STATEFUL_DOMAINS="my-project.test"
```

Modify your bootstrap/app.php file :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})
```

Modify your config/cors.php as follows :

```
'supports_credentials' => true,
```

### Install Laravel CMS

```console
composer require helori/laravel-cms

# This will create migrations for medias and updated users table
php artisan vendor:publish --tag=laravel-cms-migrations

# This will publish the app\Cms folder containing your CMS config :
php artisan vendor:publish --tag=laravel-cms-config

# This will publish the project's default config files for npm dependencies, tailwind, vite and postcss
php artisan vendor:publish --tag=laravel-cms-assets-setup --force

# This will publish sources files for the admin application
php artisan vendor:publish --tag=laravel-cms-assets-admin --force

# This will publish boilerplate files for the website (be careful when using --force to overwrite !)
php artisan vendor:publish --tag=laravel-cms-assets-website --force
```

```console
npm i
npm run dev
```

Modify your bootstrap/app.php file :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/login');
    $middleware->redirectUsersTo('/admin');
})
```

Create a user :

```console
php artisan tinker
App\Models\User::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'name' => 'John Doe',
    'email' => 'john@doe.com',
    'password' => bcrypt('password'),
]);
exit;
```