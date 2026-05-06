<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        #nprogress .bar { background: #4f46e5 !important; height: 3px !important; } /* Indigo-600 to match Tailwind primary */
        #nprogress .spinner-icon { border-top-color: #4f46e5 !important; border-left-color: #4f46e5 !important; }
    </style>
</head>

<body>
    <div class="wrapper">

        @include('layouts.partials/sidenav')

        <div class="page-content">

            @include('layouts.partials/topbar')

            <main>

                @yield('content')

            </main>

            @include('layouts.partials/footer')
            
        </div>

    </div>

    @include('layouts.partials/customizer')
    @include('layouts.partials/notifications')

    @vite(['resources/js/vendor.js', 'resources/js/app.js'])

    <script>
        // Start progress bar on page load
        NProgress.configure({ showSpinner: false });
        NProgress.start();
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.done();
        });

        // Start progress bar on link clicks
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.target && 
                !link.hasAttribute('download') && 
                link.origin === window.location.origin && 
                link.pathname !== window.location.pathname || (link && link.search !== window.location.search)) {
                NProgress.start();
            }
        });

        // Start progress bar on form submissions
        document.addEventListener('submit', function() {
            NProgress.start();
        });

        // Finish on page hide (back/forward)
        window.addEventListener('pageshow', function() {
            NProgress.done();
        });
    </script>

    @stack('js')
</body>

</html>
