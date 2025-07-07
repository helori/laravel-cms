# laravel-cms


## Installation and setup

Install Sanctum for SPA : https://laravel.com/docs/12.x/sanctum#sanctum-middleware

```console
composer require helori/laravel-cms

php artisan install:api

php artisan vendor:publish --tag=laravel-cms-migrations
php artisan vendor:publish --tag=laravel-cms-config
php artisan vendor:publish --tag=laravel-cms-assets --force

npm i --save-dev @tailwindcss/aspect-ratio @tailwindcss/forms@tailwindcss/typography @tailwindcss/vite @vitejs/plugin-vue @vuepic/vue-datepicker autoprefixer axios concurrently laravel-vite-plugin luxon numeral postcss tailwindcss:3.* tinymce:7.* vite vue:3.* vue-flatpickr-component vue-i18n vue-loader vue-router

npm run build
```
