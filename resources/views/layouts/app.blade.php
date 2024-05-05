<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    @vite('resources/css/app.css')

    <title>@yield('title')</title>
</head>
<body>
    <div class="flex">
        <x-sidebar />

        <div class="container p-5 overflow-auto h-screen">
            @yield('content')
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    @yield('script')
</body>
</html>
