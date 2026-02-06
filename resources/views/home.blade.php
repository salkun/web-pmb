<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PMB Teknik Radiologi Pencitraan') }}</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #1181C3 0%, #87CFE5 100%);
            color: white;
            padding: 100px 0;
        }
        .hero h1 {
            font-weight: 700;
            font-size: 3rem;
        }
        .btn-light-primary {
            background-color: white;
            color: #1181C3;
            font-weight: bold;
        }
    </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white shadow-sm">
    <div class="container">
      <a href="{{ url('/') }}" class="navbar-brand">
        <span class="brand-text font-weight-light">PMB <b>Teknik Radiologi Pencitraan</b></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @if(auth_check())
                <li class="nav-item">
                    @if(is_admin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="nav-link">Dashboard</a>
                    @endif
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link font-weight-bold">Login</a>
                </li>
            @endif
        </ul>
      </div>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Hero Section -->
    <div class="hero text-center">
        <div class="container">
            <h1>Penerimaan Mahasiswa Baru</h1>
            <p class="lead">Program Studi Teknik Radiologi Pencitraan D4 - Tahun Akademik {{ date('Y') }}/{{ date('Y')+1 }}</p>
            <div class="mt-4">
                <p class="mt-2 text-white-50"><small>Sudah punya akun? <a href="{{ route('login') }}" class="text-white underline">Login disini</a></small></p>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content py-5">
      <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                        <h4>1. Pendaftaran Online</h4>
                        <p class="text-muted">Isi formulir pendaftaran dan lengkapi biodata diri Anda secara online.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-upload fa-3x text-info mb-3"></i>
                        <h4>2. Upload Berkas</h4>
                        <p class="text-muted">Upload dokumen persyaratan seperti Rapor, KK, dan Surat Kesehatan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-edit fa-3x text-success mb-3"></i>
                        <h4>3. Ujian Seleksi</h4>
                        <p class="text-muted">Cetak kartu ujian dan ikuti ujian seleksi sesuai jadwal yang ditentukan.</p>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      PMB Teknik Radiologi Pencitraan v1.0
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">Teknik Radiologi Pencitraan</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
