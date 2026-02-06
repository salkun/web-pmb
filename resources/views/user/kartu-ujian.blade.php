@extends('layouts.app')

@section('title', 'Kartu Ujian')
@section('header', 'Kartu Peserta Ujian')

@push('css')
<link rel="stylesheet" href="{{ asset('css/user-pmb.css') }}">
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if($kartu)
            <div class="card status-card">
                <div class="card-header border-0 bg-white pt-4 text-center">
                    <div class="display-4 text-success mb-3"><i class="fas fa-check-circle"></i></div>
                    <h3 class="font-weight-bold text-dark">Kartu Ujian Berhasil Terbit!</h3>
                </div>
                <div class="card-body status-body text-center">
                    <div class="bg-light p-4 rounded-lg mb-4 text-left" style="border: 1px solid #e2e8f0;">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-user-circle mr-2"></i>DATA PESERTA</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150" class="text-muted">Nomor Peserta</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold text-dark">{{ $kartu->nomor_peserta }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Lengkap</td>
                                <td>:</td>
                                <td class="font-weight-bold text-dark">{{ $profile->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Program Studi</td>
                                <td>:</td>
                                <td class="font-weight-bold text-dark">{{ $profile->program_studi }} Pencitraan</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="bg-light p-4 rounded-lg mb-4 text-left" style="border: 1px solid #e2e8f0;">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-calendar-alt mr-2"></i>JADWAL UJIAN</h6>
                        <div class="row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label class="small font-weight-bold text-muted uppercase">TANGGAL UJIAN</label>
                                <div class="h5 font-weight-bold text-dark">{{ \Carbon\Carbon::parse($kartu->pengaturanUjian->tanggal_ujian)->format('d F Y') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="small font-weight-bold text-muted uppercase">LOKASI / TEMPAT</label>
                                <div class="h5 font-weight-bold text-dark">{{ $kartu->pengaturanUjian->tempat_ujian }}</div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('user.kartu-ujian.download') }}" target="_blank" class="btn btn-premium btn-premium-success btn-lg btn-block">
                        <i class="fas fa-print mr-2"></i> CETAK KARTU PESERTA (PDF)
                    </a>
                </div>
            </div>
        @else
            <div class="card status-card">
                @if($can_generate)
                    <div class="card-header card-gradient-primary border-0 py-4 text-center">
                        <h3 class="font-weight-bold m-0 text-white">Generate Kartu Ujian</h3>
                    </div>
                    <div class="card-body status-body">
                        <div class="alert alert-success border-0 mb-4" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-radius: 12px;">
                            <h5><i class="icon fas fa-award mr-2"></i> Selamat! Anda Lolos Verifikasi</h5>
                            Seluruh persyaratan administrasi Anda telah disetujui. Silakan klik tombol di bawah untuk mendapatkan Nomor Peserta.
                        </div>
                        
                        <div class="bg-light p-4 rounded-lg mb-4" style="border: 1px solid #e2e8f0;">
                            <h6 class="font-weight-bold text-dark mb-3">Informasi Jadwal Tersedia:</h6>
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted small">Tahun Akademik:</span><br>
                                    <strong>{{ $schedule->tahun_akademik }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted small">Gelombang:</span><br>
                                    <strong>{{ $schedule->gelombang }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small">Rencana Tanggal:</span><br>
                                    <strong>{{ $schedule->tanggal_ujian->format('d F Y') }}</strong>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('user.kartu-ujian.generate') }}" method="POST" class="text-center">
                            @csrf
                            <button type="submit" class="btn btn-premium btn-primary btn-block">
                                <i class="fas fa-id-card mr-2"></i> Klik Disini Untuk Generate Kartu
                            </button>
                        </form>
                    </div>
                @else
                    <div class="card-header bg-danger border-0 py-4 text-center">
                        <h3 class="font-weight-bold m-0 text-white">Status Persyaratan</h3>
                    </div>
                    <div class="card-body status-body">
                        <div class="alert alert-danger border-0 mb-4" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border-radius: 12px;">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
                        </div>
                        
                        <h6 class="font-weight-bold text-dark mb-3">Ketentuan Mendapatkan Kartu:</h6>
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Profil diri lengkap</li>
                            <li><i class="fas fa-check-circle"></i> Seluruh berkas wajib terupload</li>
                            <li><i class="fas fa-check-circle"></i> Status berkas <strong>VERIFIED</strong> oleh Admin</li>
                            <li><i class="fas fa-check-circle"></i> Jadwal ujian sedang dibuka</li>
                        </ul>
                        
                        <div class="mt-4">
                            <a href="{{ route('user.berkas') }}" class="btn btn-outline-primary btn-block py-2" style="border-radius: 10px;">
                                Periksa Status Berkas Anda
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
