<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>Si NggoRat &mdash;</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('frontend.includes.meta')

    <!-- Shortcut Icon -->
    <link rel="icon" type="image/ico" href="{{asset('img/favicon.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('before-styles')

    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/multitekol-bg.css">
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/icomoon.css">

    <link rel="stylesheet" href="/css/frontend.css">
    <link rel="stylesheet" href="/css/style.css">

    @stack('after-styles')

    <x-google-analytics />
</head>

<body>

    @include('frontend.includes.header')

    <!-- <x-preloader /> -->

    <main>
        @yield('content')
    </main>

    @include('frontend.includes.footer')

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

</body>

<!-- Scripts -->
@stack('before-scripts')
    <script src="{{ mix('js/frontend.js') }}"></script>        

    <script src="/js/main.js"></script>

    <script type="text/javascript" src="/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.js"></script>
    
    <link rel="stylesheet" href="/css/bootstrap-multiselect.css" type="text/css"/>

@stack('after-scripts')

</html>