@extends('layouts.app')

@section('title', 'Detail Pendaftar')
@section('header', 'Detail Pendaftar')

@push('css')
<style>
    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(135deg, #0082CB 0%, #0056b3 100%);
        color: white;
        padding: 30px;
        position: relative;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        color: #0082CB;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        margin-bottom: 15px;
    }
    .profile-info h3 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .profile-info p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #95a5a6;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 5px;
        display: block;
    }
    .info-value {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 500;
        margin-bottom: 20px;
        display: block;
    }
    .section-title {
        border-left: 4px solid #0082CB;
        padding-left: 15px;
        margin-bottom: 25px;
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 12px;
        border-radius: 20px;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(5px);
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.students.index') }}" class="btn btn-light shadow-sm">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card detail-card mb-4">
            <div class="profile-header text-center d-flex flex-column align-items-center">
                <div class="profile-avatar">
                    @if($user->profile && $user->profile->foto_profil)
                        <img src="{{ asset('storage/' . $user->profile->foto_profil) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        {{ strtoupper(substr($user->profile->nama_lengkap ?? $user->no_pendaftaran, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h3>{{ $user->profile->nama_lengkap ?? 'Belum Isi Nama' }}</h3>
                    <p>{{ $user->no_pendaftaran }}</p>
                </div>
                <div class="badge-status">
                    @if($user->profile && $user->profile->is_complete)
                        <i class="fas fa-check-circle mr-1"></i> Data Lengkap
                    @else
                        <i class="fas fa-exclamation-circle mr-1"></i> Belum Lengkap
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <span class="info-label">EMAIL AKTIF</span>
                    <span class="info-value">{{ $user->profile->email_aktif ?? '-' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">NOMOR HP</span>
                    <span class="info-value">{{ $user->profile->no_hp_aktif ?? '-' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">AKUN DIBUAT</span>
                    <span class="info-value">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2 d-flex justify-content-center mt-3">
                    <a href="{{ route('admin.students.edit', $user->id) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card detail-card p-4">
            <div class="section-title">Data Kelahiran & Jenis Kelamin</div>
            <div class="row">
                <div class="col-md-6">
                    <span class="info-label">TEMPAT LAHIR</span>
                    <span class="info-value">{{ $user->profile->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="col-md-6">
                    <span class="info-label">TANGGAL LAHIR</span>
                    <span class="info-value">{{ $user->profile->tanggal_lahir ? $user->profile->tanggal_lahir->format('d F Y') : '-' }}</span>
                </div>
                <div class="col-md-6">
                    <span class="info-label">JENIS KELAMIN</span>
                    <span class="info-value">{{ ($user->profile->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($user->profile->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}</span>
                </div>
            </div>
            
            <div class="section-title mt-4">Pendidikan</div>
            <div class="row">
                <div class="col-md-6">
                    <span class="info-label">ASAL SEKOLAH</span>
                    <span class="info-value">{{ $user->profile->asal_sekolah ?? '-' }}</span>
                </div>
                <div class="col-md-6">
                    <span class="info-label">TAHUN KELULUSAN</span>
                    <span class="info-value">{{ $user->profile->tahun_kelulusan ?? '-' }}</span>
                </div>
                <div class="col-md-12">
                    <span class="info-label">PROGRAM STUDI PILIHAN</span>
                    <span class="info-value">{{ $user->profile->program_studi ?? '-' }}</span>
                </div>
            </div>
            
            <div class="section-title mt-4">Alamat Tinggal</div>
            <div class="row">
                <div class="col-md-12">
                    <span class="info-label">ALAMAT LENGKAP</span>
                    <span class="info-value">{{ $user->profile->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
