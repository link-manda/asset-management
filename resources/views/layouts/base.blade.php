<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>
<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body>
    @yield('content')

    @include('layouts.partials/customizer')

    @vite(['resources/js/vendor.js', 'resources/js/app.js'])
    @stack('js')
</body>
</html>