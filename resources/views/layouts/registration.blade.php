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

    <script>
        window.env = {
            guest_booking_code: '{{ config('settings.guest_booking_code') }}',
            guest_booking_limit: '{{ config('settings.guest_booking_limit') }}',
            member_booking_limit: '{{ config('settings.member_booking_limit') }}',
            cluster_groups: {!! json_encode(config('clustergroups')) !!},
            display_disclosure_prompt: '{{ config('settings.display_disclosure_prompt') }}',
            year:'{{ config('settings.year') }}',
            theme:'{{ config('settings.theme') }}',
            event_date:'{{ config('settings.event_date') }}',
            hybrid_registration_deadline: '{{ config('settings.hybrid_registration_deadline') }}',
            fb_group_url: '{{ config('settings.fb_group_url') }}',
            zoom: {!! json_encode(config('settings.zoom_details')) !!},
        };
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main style="min-height: 100vh; background-color: #ebebeb" class="py-4">
            @yield('content')
        </main>

        @yield('footer')
    </div>
</body>
<!-- import JavaScript -->
{{-- <script src="https://unpkg.com/element-ui/lib/index.js"></script> --}}
</html>
