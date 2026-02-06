@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Overview Statistik')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush

@section('content')
<div class="row">
    <!-- Total User -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-blue shadow-sm">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <p>Total User</p>
                <h3>{{ \App\Models\User::count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Berkas Pending -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-sky shadow-sm">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-info">
                <p>Berkas Pending</p>
                <h3>{{ \App\Models\BerkasMahasiswa::where('status', 'pending')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Lulus Ujian -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-emerald shadow-sm">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <p>Lulus Ujian</p>
                <h3>{{ \App\Models\Kelulusan::where('status', 'lulus')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Admin -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-rose shadow-sm">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <p>Admin</p>
                <h3>{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Log Aktivitas -->
    <div class="col-md-8">
        <div class="card recent-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Log Aktivitas Terbaru</h5>
                <a href="#" class="btn btn-link btn-sm text-info font-weight-bold">Lihat Semua</a>
            </div>
            <div class="card-body">
                @php
                    $logs = \App\Models\ActivityLog::latest()->take(5)->get();
                @endphp
                @forelse($logs as $log)
                    <div class="activity-item">
                        <div class="activity-dot shadow-sm"></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="font-weight-bold text-dark">{{ $log['username'] }}</span>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</small>
                            </div>
                            <p class="m-0 text-muted small">{{ $log['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-history text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted">Belum ada riwayat aktivitas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card recent-card bg-gradient-blue text-white">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="card-title text-white">Aksi Cepat</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <p class="small opacity-75">Gunakan shortcut ini untuk akses cepat ke fitur manajemen utama.</p>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.users.index') }}" class="list-group-item bg-transparent text-white border-0 px-0 d-flex align-items-center hover-opacity-50">
                        <i class="fas fa-plus-circle mr-3"></i> Tambah User Baru
                    </a>
                    <a href="{{ route('admin.berkas.index') }}" class="list-group-item bg-transparent text-white border-0 px-0 d-flex align-items-center">
                        <i class="fas fa-check-double mr-3"></i> Periksa Berkas Pending
                    </a>
                    <a href="{{ route('admin.ujian.index') }}" class="list-group-item bg-transparent text-white border-0 px-0 d-flex align-items-center">
                        <i class="fas fa-calendar-plus mr-3"></i> Atur Jadwal Ujian
                    </a>
                    <a href="{{ route('admin.kelulusan.index') }}" class="list-group-item bg-transparent text-white border-0 px-0 d-flex align-items-center">
                        <i class="fas fa-clipboard-check mr-3"></i> Input Hasil Kelulusan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
