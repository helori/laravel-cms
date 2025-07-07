@extends('laravel-cms::layout')
@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-primary-900 py-10 flex flex-col justify-center">
    <div class="mx-auto">
        @include('laravel-cms::logo', [
            'align' => 'center'
        ])
    </div>
    <div class="sm:max-w-sm sm:mx-auto mt-10 sm:rounded-md py-4 sm:py-6 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 dark:text-white shadow border-b border-gray-100 dark:border-gray-700">
        @yield('auth-content')
    </div>
</div>

@endsection
