<!DOCTYPE html>
<html>
<head>

    <title>{{ config('app.name', '') }} Admin</title>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="white" />
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />

    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css') }}">

</head>

<body id="admin">

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <!-- Menu  -->
    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <nav id="menu" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#admin-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('admin-home') }}">
                    {{ config('app.name', '') }} Admin
                </a>
            </div>

            <div id="admin-menu" class="collapse navbar-collapse">

                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <ul class="nav navbar-nav navbar-right">

                    @if (Auth::guard('admin')->guest())
                        <li><a href="{{ route('admin-login') }}"><i class="fa fa-sign-in"></i> Connexion</a></li>
                    @else

                        <li class="{{ Request::route()->getName() == 'admin-medias' ? 'active' : '' }}">
                            <a href="{{ route('admin-medias') }}"><i class="fa fa-sitemap"></i> Medias</a>
                        </li>

                        <li class="{{ Request::route()->getName() == 'admin-pages' ? 'active' : '' }}">
                            <a href="{{ route('admin-pages') }}"><i class="fa fa-sitemap"></i> Pages</a>
                        </li>

                        <li class="{{ Request::route()->getName() == 'admin-blog' ? 'active' : '' }}">
                            <a href="{{ route('admin-blog') }}"><i class="fa fa-sitemap"></i> Blog</a>
                        </li>

                        @if(count($fieldsets) > 0)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-cubes"></i> Elements <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @foreach($fieldsets as $fieldset)
                                <li>
                                    <a href="{{ route('admin-elements', ['slug' => $fieldset->slug]) }}">{{ $fieldset->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        <li>
                            <a href="{{ url('/') }}" target="_blank"><i class="fa fa-eye"></i> Voir le site</a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-user"></i> {{ Auth::guard('admin')->user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="{{ Request::route()->getName() == 'admin-fieldsets' ? 'active' : '' }}">
                                    <a href="{{ route('admin-fieldsets') }}"><i class="fa fa-list"></i> Fieldsets</a>
                                </li>
                                <li>
                                    <a href="{{ url('/logout') }}" 
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> Déconnexion
                                    </a>
                                    <form id="logout-form" action="{{ route('admin-logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </nav>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <!-- Content -->
    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <main id="admin-content" style="padding: 30px 0">
        <div class="container">
            @yield('content')
        </div>
    </main>


    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <!-- Scripts -->
    <!-- - - - - - - - - - - - - - - - - - - - - - - - - -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script src="{{ mix('js/admin.js') }}"></script>

</body>
