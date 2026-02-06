<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link p-0" data-toggle="dropdown" href="#">
                    @php
                        $userSession = auth_user();
                        // User has "filled" their name if it's not empty and not the same as their registration number
                        $hasName = !empty($userSession['nama_lengkap']) && ($userSession['nama_lengkap'] !== $userSession['no_pendaftaran']);
                        $displayName = $hasName ? $userSession['nama_lengkap'] : $userSession['no_pendaftaran'];
                        $displaySub = $hasName ? $userSession['no_pendaftaran'] : (is_admin() ? 'Administrator' : 'User');
                        $photo = $userSession['foto_profil'] ?? null;
                    @endphp
                    <div class="user-panel">
                        <div class="user-avatar">
                            @if($photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Profile Profile">
                            @else
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            @endif
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ $displayName }}</div>
                            <div class="user-role">{{ $displaySub }}</div>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @if(is_admin())
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i> Lihat Profile
                        </a>
                        <div class="dropdown-divider"></div>
                    @else
                        <a href="{{ route('user.profile') }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i> Lihat Profile
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4">
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="brand-link" style="height: 100px; padding: 0; border-bottom: 2px solid #0082CB; background: linear-gradient(135deg, #011E41 0%, #0082CB 100%) !important; display: flex; align-items: center; justify-content: center; overflow: hidden;">
            <img src="{{ asset('logo pmb putih.png') }}" alt="Logo PMB" style="width: 180%; max-height: 180px; height: auto; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3)); transition: transform 0.3s ease;">
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    @if(is_admin())
                        <li class="nav-header">MENU UTAMA</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.berkas.index') }}" class="nav-link {{ request()->routeIs('admin.berkas.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>Verifikasi Berkas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.ujian.index') }}" class="nav-link {{ request()->routeIs('admin.ujian.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan Ujian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Data Pendaftar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.kelulusan.index') }}" class="nav-link {{ request()->routeIs('admin.kelulusan.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-trophy"></i>
                                <p>Input Kelulusan</p>
                            </a>
                        </li>
                    @endif

                    @if(is_user())
                        <li class="nav-header">MENU UTAMA</li>
                        <li class="nav-item">
                            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>Profil Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.berkas') }}" class="nav-link {{ request()->routeIs('user.berkas') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cloud-upload-alt"></i>
                                <p>Upload Berkas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.kartu-ujian') }}" class="nav-link {{ request()->routeIs('user.kartu-ujian') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>Kartu Ujian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.pengumuman') }}" class="nav-link {{ request()->routeIs('user.pengumuman') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>Pengumuman</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-header">SISTEM</li>
                    <li class="nav-item mt-auto">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-sidebar">
                            @csrf
                            <button type="submit" class="nav-link bg-transparent border-0 w-100 text-left text-danger" style="outline: none;">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('header', 'Dashboard')</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">PMB Teknik Radiologi Pencitraan</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            toast: true,
            position: 'top-end'
        });
    @endif
</script>

@stack('scripts')
</body>
</html>
