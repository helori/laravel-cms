<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=0, shrink-to-fit=no" />

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />

    <meta property="og:image" content="" />
    <meta property="og:image:type" content="" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="translucent black">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />
    <link rel="apple-touch-icon" href="{{ asset('touch-icon.png') }}" type="image/png" />

    <link rel="canonical" href="{{ Request::url() }}">

    @vite('resources/css/website.css')

</head>

<body class="bg-gray-100">

    <main class="w-1/3 h-72 mx-auto my-20 p-16 bg-white shadow-lg rounded-lg">
        @yield('content')
    </main>

    @vite('resources/js/website.js')

</body>
</html>
