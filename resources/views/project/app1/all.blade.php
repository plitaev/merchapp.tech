<!DOCTYPE html>
<html prefix='og: http://ogp.me/ns#' class="app" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{env('APP_DESC')}}" />

    <title>{{env('APP_NAME')}} @hasSection('title') - @yield('title') @endif</title>

    <!-- Styles -->
    @vite('resources/css/app.css')

    @hasSection('head') @yield('head') @endif
</head>

<body>
<div id="app">
    @yield('body')
</div>
</body>
</html>
