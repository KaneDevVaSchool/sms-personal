<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'OPS Platform') }}</title>

        <link rel="shortcut icon" href="{{ asset('jidox/layouts/assets/images/favicon.ico') }}">

        <script src="{{ asset('jidox/layouts/assets/js/config.js') }}"></script>

        <link href="{{ asset('jidox/layouts/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{ asset('jidox/layouts/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        @routes
        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        <script src="{{ asset('jidox/layouts/assets/js/vendor.min.js') }}"></script>
        <script src="{{ asset('jidox/layouts/assets/js/app.min.js') }}"></script>
    </body>
</html>
