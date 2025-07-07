<!DOCTYPE html>
<html class="{{ (isset($user) && $user->dark_mode) ? 'dark' : '' }}">
<head>

    <title>{{ config('app.name') }}</title>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ Request::url() }}">

    @vite('resources/css/admin.css')

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />
    <link rel="apple-touch-icon" href="{{ asset('touch-icon.png') }}" type="image/png" />

</head>

<body class="m-0 bg-gray-200 dark:bg-gray-700 select-none subpixel-antialiased">

    @yield('content')

    <script>
        window.cms = {!! json_encode($config) !!};
    </script>

    @vite('resources/js/admin.js')

</body>

