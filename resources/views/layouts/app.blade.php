{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>


--}}





<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paid to free - Bootstrap 5 Admin Dashboard HTML Template</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/') }}/images/favicon.png" sizes="16x16" />
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/remixicon.css" />
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/bootstrap.min.css" />
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/apexcharts.css" />
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/dataTables.min.css" />
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/editor-katex.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/editor.atom-one-dark.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/editor.quill.snow.css" />
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/flatpickr.min.css" />
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/full-calendar.css" />
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/jquery-jvectormap-2.0.5.css" />
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/magnific-popup.css" />
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/slick.css" />
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/prism.css" />
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/file-upload.css" />

    <link rel="stylesheet" href="{{ asset('assets/') }}/css/audioplayer.css" />
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/style.css" />
</head>

<body>

    @include('layouts.partials.sidebar')
    <main class="dashboard-main">
        @include('layouts.partials.navbar')

        <div class="dashboard-main-body">
            {{-- <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Dashboard</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">eCommerce</li>
                </ul>
            </div> --}}

            @yield('content')
        </div>

        @include('layouts.partials.footer')
    </main>

    <!-- jQuery library js -->
    <script src="{{ asset('assets/') }}/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/') }}/js/bootstrap.bundle.min.js"></script>
    <!-- Apex Chart js -->
    <script src="{{ asset('assets/') }}/js/apexcharts.min.js"></script>
    <!-- Data Table js -->
    <script src="{{ asset('assets/') }}/js/dataTables.min.js"></script>
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/') }}/js/iconify-icon.min.js"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/') }}/js/jquery-ui.min.js"></script>
    <!-- Vector Map js -->
    <script src="{{ asset('assets/') }}/js/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="{{ asset('assets/') }}/js/jquery-jvectormap-world-mill-en.js"></script>
    <!-- Popup js -->
    <script src="{{ asset('assets/') }}/js/magnifc-popup.min.js"></script>
    <!-- Slick Slider js -->
    <script src="{{ asset('assets/') }}/js/slick.min.js"></script>
    <!-- prism js -->
    <script src="{{ asset('assets/') }}/js/prism.js"></script>
    <!-- file upload js -->
    <script src="{{ asset('assets/') }}/js/file-upload.js"></script>
    <!-- audioplayer -->
    <script src="{{ asset('assets/') }}/js/audioplayer.js"></script>

    <!-- main js -->
    <script src="{{ asset('assets/') }}/js/app.js"></script>

    <script src="{{ asset('assets/') }}/js/homeThreeChart.js"></script>

    @yield('scripts')
</body>

</html>