{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


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

    {{-- @include('layouts.partials.sidebar') --}}
    {{-- <main class="dashboard-main"> --}}
        {{-- @include('layouts.partials.navbar') --}}

        <div class="dashboard-main-body">
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Daftar Akun</h4>
                            </div>
                            <div class="card-body">
                                @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('pendaftaran.register') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text"
                                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                                            id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                            required>
                                        @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" required>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi
                                            Password</label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" required>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input @error('terms') is-invalid @enderror"
                                            type="checkbox" value="1" id="terms" name="terms" {{ old('terms')
                                            ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms">
                                            Saya setuju dengan <a href="#">syarat dan ketentuan</a>
                                        </label>
                                        @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-1"></i> Daftar
                                        </button>
                                    </div>

                                    <div class="mt-3 text-center">
                                        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.partials.footer') --}}
        {{--
    </main> --}}

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