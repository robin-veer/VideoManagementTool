<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>Video Manager</title>

    <link href="{{ @asset('css/app.css') }}" rel="stylesheet">
    @yield('header')
</head>
<body>