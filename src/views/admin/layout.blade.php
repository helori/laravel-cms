<!DOCTYPE html>
<html>
<head>

    <title>Administration</title>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="white" />
    <link rel="shortcut icon" href="favicon.png" type="image/png" />

    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css') }}">

</head>

<body id="admin">

<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- Menu  -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-menu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/admin') }}">
                Admin
            </a>
        </div>

        <div class="collapse navbar-collapse" id="nav-menu">
            <div class="navbar-right">
                <a href="{{ url('/admin/crud/admins/items') }}" class="btn btn-default icon-only navbar-btn"><i class="fa fa-key"></i></a>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-default icon-only navbar-btn"><i class="fa fa-desktop"></i></a>
                <a id="admin-logout" href="{{ url('/admin/logout') }}" class="btn btn-danger icon-only navbar-btn"><i class="fa fa-power-off"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                
                @include('laravel-admin::menu')

            </ul>
        </div>
    </div>
</nav>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- Content -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
@yield('content')


<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- Scripts -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
<script src="{{ mix('js/admin.js') }}"></script>
<script src="{{ url('tinymce/tinymce.min.js') }}"></script>

</body>
