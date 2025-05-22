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

    <title>{{ config('app.name', 'Laravel') }} - Attendance</title>

    @if (auth()->check())
    <script>
        window.auth_user = {!! json_encode(auth()->user()->load(['permissions'])); !!};
        window.env = {
            cluster_groups: {!! json_encode(config('clustergroups')) !!},
            year:'{{ config('settings.year') }}'
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
    <el-container style="border: 1px solid #eee" id="app">
        <el-container>
            @if (Auth::user())
            <el-header style="height: auto; text-align: right; font-size: 12px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04); border-bottom: 1px solid #DCDFE6;">
                <span class="brand-text font-weight-light"><span style="font-weight: 800; text-shadow: black 0px 0px; letter-spacing: 0.05em; color: dimgray;" class="float-start p-3 text-uppercase">{{ $event->name }}</span>
                <li class="nav-item dropdown" style="list-style: none; padding: 15px;">
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
            </el-header>
            @endif
          
            <main class="py-4">
                @yield('content')
            </main>

            @yield('footer')
        </el-container>
      </el-container>
</body>
<!-- import JavaScript -->
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
</html>
