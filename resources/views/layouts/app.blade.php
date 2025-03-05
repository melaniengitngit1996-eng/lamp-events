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


    <el-container style="border: 1px solid #eee"  id="app">
        <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
            <a href="{{ route('home') }}" style="display: block;font-size: 1.125rem;line-height: 1.3;padding: 0.82rem 0.5rem;transition: width 0.3s ease-in-out;white-space: nowrap;text-align: center;text-decoration: none;color: cornflowerblue;">
                <span class="logo-lg">
                    <span class="brand-text font-weight-light"><span style="font-weight: 600;">LAMP CHURCH</span>
                </span>
            </a>
            
            <el-menu :default-openeds="['4']">
                <el-menu-item>
                    <template slot="title"><img class="mb-1" height="17" width="20" src="/images/portfolio.png" style="margin-right: 10px"/>Dashboard</template>
                </el-menu-item>

                <el-menu-item>
                    <template slot="title"><img class="mb-1" height="17" width="20" src="/images/database.png" style="margin-right: 10px"/>AWTA Data</template>
                </el-menu-item>
                
                {{-- <el-submenu index="4">
                    <template slot="title"><img class="mb-1" height="20" width="24" src="/images/users.png" style="margin-right: 10px"/></i>Members</template>
                    <el-menu-item-group>
                        <template slot="title">Group 1</template>
                        <el-menu-item index="3-1">Option 1</el-menu-item>
                        <el-menu-item index="3-2">Option 2</el-menu-item>
                    </el-menu-item-group>
                    <el-menu-item-group title="Group 2">
                        <el-menu-item index="3-3">Option 3</el-menu-item>
                    </el-menu-item-group>
                    <el-submenu index="3-4">
                        <template slot="title">Option 4</template>
                        <el-menu-item index="3-4-1">Option 4-1</el-menu-item>
                    </el-submenu>
                </el-submenu> --}}

                <el-menu-item>
                    <template slot="title"><img class="mb-1" height="17" width="20" src="/images/calendar.png" style="margin-right: 10px"/>Events</template>
                </el-menu-item>
            </el-menu>
        </el-aside>
        
        <el-container>
            <el-header style="height: auto; text-align: right; font-size: 12px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .12), 0 0 6px 0 rgba(0, 0, 0, .04); border-bottom: 1px solid #DCDFE6;">
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
            </el-header>
          
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
