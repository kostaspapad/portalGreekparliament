<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- lang="{{ app()->getLocale() }}" -->
    <head>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-58724981-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-58724981-1');
        </script>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Greekparliament.info</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

        <!-- CSS -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>

    <body style="margin-bottom: 0!important;">

        <div id="app" class="content-bg-color">
        
            <!-- Main nav -->
            <nav class="navbar navbar-expand-md py-0 navbar-light navbar-laravel navbar-bg-color">
                <div class="container-fluid ml-0">
                    <a class="navbar-brand logo" style="color:#fff" href="{{ url('/') }}">
                        Greekparliament.info
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" 
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                            aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    {{-- <speechsearch></speechsearch> --}}
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                                </li>
                            @else

                                <li class="nav-item dropdown">

                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <!-- <a class="dropdown-item" href="/dashboard">Dashboard</a> -->

                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('auth.logout') }}
                                        </a>
                                        
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if (app()->getLocale() === 'en')
                                        <img src="/img/flags/flag_uk.png" alt="{{__('navbar.lang')}}">
                                    @elseif (app()->getLocale() === 'el')
                                        <img src="/img/flags/flag_gr.png" alt="{{__('navbar.lang')}}">
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="/locale/en">{{ __('navbar.lang_en') }}</a>
                                        <a class="dropdown-item" href="/locale/el">{{ __('navbar.lang_el') }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Language dropdown -->
                
            </nav>
            <!-- Main nav -->
            <hr class="divider" />
            <!-- Menu -->
            <nav class="navbar navbar-expand-md py-0 navbar-light navbar-laravel navbar-bg-color">
                <div class="container ml-0">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::path() === 'conferences' ? 'active' : null }}" href="/conferences">{{ __('navbar.conferences') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::path() === 'parties' ? 'active' : null }}" href="/parties">{{ __('navbar.political_parties') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::path() === 'speakers' ? 'active' : null }}" href="/speakers">{{ __('navbar.speakers') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::path() === 'speeches' ? 'active' : null }}" href="/speeches">{{ __('navbar.speeches') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::path() === 'about' ? 'active' : null }}" href="/about">{{ __('navbar.about') }}</a>
                                </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Menu -->

            <div class="content mb-5" id="main_app">
                @yield('content')
            </div>

           
            <!-- <main class="py-4">
                @yield('content')
            </main> -->
        </div>
         <!-- Footer -->
         <footer class="footer page-footer font-small pt-4">

                <!-- Footer Links -->
                <div class="container-fluid text-center text-md-left">

                    <!-- Grid row -->
                    <div class="row">

                        <!-- Grid column -->
                        <div class="col-md-6 mt-md-0 mt-3">

                            <!-- Content -->
                            <h5 class="font-weight-bold">Greekparliament.info</h5>
                            <p>{{ __('footer.about_text') }}</p>

                        </div>
                        <!-- Grid column -->

                        <hr class="clearfix w-100 d-md-none pb-3">

                        <!-- Grid column -->
                        <div class="col-md-3 mb-md-0 mb-3">

                            <!-- Links -->
                            <!-- <h5 class="text-uppercase">Links</h5> -->

                            <ul class="list-unstyled">
                                <li>
                                    <a class="nav-link {{ Request::path() === '/about' ? 'active' : null }}" href="/about">{{ __('footer.about_us') }}</a>
                                </li>
                                <li>
                                    <a class="nav-link {{ Request::path() === '/news' ? 'active' : null }}" href="/news">{{ __('footer.news') }}</a>
                                </li>
                                <li>
                                    <a class="nav-link {{ Request::path() === '/contact' ? 'active' : null }}" href="/contact">{{ __('footer.contact') }}</a>
                                </li>
                                <li>
                                    <a class="nav-link {{ Request::path() === '/donate' ? 'active' : null }}" href="/donate">{{ __('footer.donate') }}</a>
                                </li>
                                <li>
                                    <a class="nav-link {{ Request::path() === '/policy' ? 'active' : null }}" href="/policy">{{ __('footer.privacy_policy') }}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <!-- <div class="col-md-3 mb-md-0 mb-3">

                            
                            <h5 class="text-uppercase">Links</h5>

                            <ul class="list-unstyled">
                                <li>
                                    <a href="#!">Link 2</a>
                                </li>
                                <li>
                                    <a href="#!">Link 3</a>
                                </li>
                                <li>
                                    <a href="#!">Link 4</a>
                                </li>
                            </ul>

                        </div> -->
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->

                </div>
                <!-- Footer Links -->

                <!-- Copyright -->
                <!-- <div class="footer-copyright text-center py-3">© 2018 Copyright: -->
                <!-- </div> -->
                <!-- Copyright -->
                
            </footer>
            <!-- Footer -->
    </body>
</html>
