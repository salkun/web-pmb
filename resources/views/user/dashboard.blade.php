@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('header', 'Beranda')

@push('css')
<link rel="stylesheet" href="{{ asset('css/user-pmb.css') }}">
@endpush

@section('content')
<div class="dashboard-wrapper">
    <!-- Hero Section -->
    <div class="welcome-banner">
        <h1>Halo, {{ $user->profile->nama_lengkap ?? $user->username }}!</h1>
        <p>Pantau proses pendaftaran Anda di sini. Semangat mengikuti seleksi Penerimaan Mahasiswa Baru Teknik Radiologi Pencitraan!</p>
    </div>

    <!-- Progress Tracker -->
    <div class="progress-section">
        <div class="progress-info">
            <h6>STATUS PENDAFTARAN</h6>
            <span>{{ $status_label }}</span>
        </div>
        <div class="custom-progress">
            <div class="custom-progress-bar" style="width: {{ $progress }}%"></div>
        </div>
        <div class="font-weight-bold text-primary">{{ $progress }}%</div>
    </div>

    <!-- Quick Links / Status Cards -->
    <div class="menu-grid">
        <!-- Profile Card -->
        <a href="{{ route('user.profile') }}" class="menu-card">
            <div class="icon-box" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fas fa-user-edit"></i>
            </div>
            <h4>Profil Peserta</h4>
            <p>Lengkapi data pribadi, pendidikan, dan kontak Anda agar dapat diverifikasi.</p>
            <div class="status-indicator {{ $user->profile && $user->profile->is_complete ? 'text-success' : 'text-warning' }}">
                <i class="fas {{ $user->profile && $user->profile->is_complete ? 'fa-check-circle' : 'fa-clock' }}"></i>
                {{ $user->profile && $user->profile->is_complete ? 'LENGKAP' : 'BELUM LENGKAP' }}
            </div>
        </a>

        <!-- Document Card -->
        <a href="{{ route('user.berkas') }}" class="menu-card">
            <div class="icon-box" style="background: rgba(0, 130, 203, 0.1); color: #0082CB;">
                <i class="fas fa-file-upload"></i>
            </div>
            <h4>Pemberkasan</h4>
            <p>Unggah berkas persyaratan digital Anda. Pastikan dokumen terbaca dengan jelas.</p>
            <div class="status-indicator {{ $user_verified_berkas >= $total_required && $total_required > 0 ? 'text-success' : 'text-primary' }}">
                <i class="fas {{ $user_verified_berkas >= $total_required && $total_required > 0 ? 'fa-check-circle' : 'fa-sync' }}"></i>
                {{ $user_verified_berkas }}/{{ $total_required }} BERKAS TERVERIFIKASI
            </div>
        </a>

        <!-- Exam Card -->
        <a href="{{ route('user.kartu-ujian') }}" class="menu-card">
            <div class="icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-id-card"></i>
            </div>
            <h4>Kartu Ujian</h4>
            <p>Unduh kartu tanda peserta ujian Anda setelah seluruh berkas terverifikasi.</p>
            <div class="status-indicator {{ $user->kartuUjian ? 'text-success' : 'text-muted' }}">
                <i class="fas {{ $user->kartuUjian ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                {{ $user->kartuUjian ? 'SIAP DICETAK' : 'BELUM TERSEDIA' }}
            </div>
        </a>

        <!-- Announcement Card -->
        <a href="{{ route('user.pengumuman') }}" class="menu-card">
            <div class="icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="fas fa-bullhorn"></i>
            </div>
            <h4>Hasil Seleksi</h4>
            <p>Cek pengumuman kelulusan dan hasil nilai ujian Anda melalui halaman ini.</p>
            <div class="status-indicator {{ $user->kelulusan && $user->kelulusan->is_published ? 'text-danger' : 'text-muted' }}">
                <i class="fas {{ $user->kelulusan && $user->kelulusan->is_published ? 'fa-check-circle' : 'fa-clock' }}"></i>
                {{ $user->kelulusan && $user->kelulusan->is_published ? 'LIHAT HASIL' : 'MENUNGGU HASIL' }}
            </div>
        </a>
    </div>

    <!-- Support Section -->
    <div class="workflow-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="workflow-title">
                    <i class="fas fa-headset"></i>
                    Butuh Bantuan?
                </h3>
                <p class="text-muted">Jika Anda memiliki kendala atau pertanyaan seputar proses pendaftaran PMB Online, tim kami siap membantu Anda setiap hari (08.00 - 16.00 WIB).</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="https://wa.me/6282114989003" target="_blank" class="btn btn-success px-4 font-weight-bold" style="border-radius: 12px;">
                        <i class="fab fa-whatsapp mr-2"></i> WHATSAPP CS
                    </a>
                    <a href="#" class="btn btn-outline-primary px-4 font-weight-bold" style="border-radius: 12px;">
                        <i class="fas fa-envelope mr-2"></i> info@atrodas.ac.id
                    </a>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center">
                <img src="https://img.freepik.com/free-vector/customer-support-concept-illustration_114360-5025.jpg" alt="Help" class="img-fluid" style="max-height: 200px; border-radius: 20px;">
            </div>
        </div>
    </div>
</div>
@endsection
