<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ secure_asset('assets/bootstrap_5.0.2/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/dataTables/css/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/dataTables/css/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/sweetalert2/css/minimal.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/select2_4.1.0/css/select2.min.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary mb-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ secure_url('/') }}">Aplikasi Transaksi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php $currentUrl = secure_url(request()->path()) ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold {{ $currentUrl == secure_url('/') ? 'active' : '' }}" href="{{ secure_url('/') }}">List Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold {{ $currentUrl == secure_url('/form_transaksi') ? 'active' : '' }}" href="{{ secure_url('/form_transaksi') }}">Form Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold {{ $currentUrl == secure_url('/list_barang') ? 'active' : '' }}" href="{{ secure_url('/list_barang') }}">Daftar Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold {{ $currentUrl == secure_url('/list_customer') ? 'active' : '' }}" href="{{ secure_url('/list_customer') }}">Daftar Customer</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
    @yield('modals')
    <script src="{{ secure_asset('assets/jquery_3.7.1/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ secure_asset('assets/bootstrap_5.0.2/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ secure_asset('assets/dataTables/js/dataTables.min.js') }}"></script>
    <script src="{{ secure_asset('assets/dataTables/js/dataTables.boostrap5.js') }}"></script>
    <script src="{{ secure_asset('assets/dataTables/js/dataTables.responsive.js') }}"></script>
    <script src="{{ secure_asset('assets/dataTables/js/responsive.bootstrap5.js') }}"></script>
    <script src="{{ secure_asset('assets/sweetalert2/js/sweetalert2.min.js') }}"></script>
    <script src="{{ secure_asset('assets/select2_4.1.0/js/select2.min.js') }}"></script>
    @yield('scripts')
</body>

</html>