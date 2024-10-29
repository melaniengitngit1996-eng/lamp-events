<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:image" content="https://lampawta.com/images/meta_banner.png" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://lampawta.com/registration"/>
    <meta property="og:title" content="Annual Worship and Thanksgiving {{ config('settings.year') }}" />
    <meta property="og:description" content="{{ config('settings.theme') }}"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    @if (auth()->check())
    <script>
        window.auth_user = {!! json_encode(auth()->user()->load(['permissions'])); !!};
        window.env = {
            guest_booking_code: '{{ config('settings.guest_booking_code') }}',
            guest_booking_limit: '{{ config('settings.guest_booking_limit') }}',
            member_booking_limit: '{{ config('settings.member_booking_limit') }}',
            cluster_groups: {!! json_encode(config('clustergroups')) !!},
            year:'{{ config('settings.year') }}',
            theme:'{{ config('settings.theme') }}',
            fb_group_url: '{{ config('settings.fb_group_url') }}',
            zoom: {!! json_encode(config('settings.zoom_details')) !!},
        };
    </script>
    @endif

    <!-- Scripts -->
    @yield('scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif --}}

                            @if (Route::has('registration'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('registration') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Registrations') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('activities') }}">
                                        {{ __('Activities') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('configurations') }}">
                                        {{ __('Configurations') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        @yield('footer')
    </div>
</body>
<!-- import JavaScript -->
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
</html>
