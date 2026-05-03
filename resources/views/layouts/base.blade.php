<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>
<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body>
    @yield('content')

    @include('layouts.partials/customizer')

    @vite(['resources/js/vendor.js', 'resources/js/app.js'])
    @stack('js')
</body>
</html>