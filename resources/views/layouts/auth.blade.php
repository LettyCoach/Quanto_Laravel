<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('public/css/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('public/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>
<body class="min-vh-100 d-flex justify-content-center align-items-center" style="background-color: #ccc;">

@yield('main-content')

<!-- Scripts -->
<script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/lib/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
