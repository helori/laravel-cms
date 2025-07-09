# laravel-cms


## Installation and setup

Install Sanctum for SPA : https://laravel.com/docs/12.x/sanctum#sanctum-middleware

```console
composer require helori/laravel-cms

php artisan install:api

php artisan vendor:publish --tag=laravel-cms-migrations
php artisan vendor:publish --tag=laravel-cms-config
php artisan vendor:publish --tag=laravel-cms-assets --force
php artisan vendor:publish --tag=laravel-cms-view

npm i --save-dev helorui axios luxon numeral postcss tailwindcss:3.* tinymce:7.* vue:3.* vue-flatpickr-component vue-i18n

npm run build
```
