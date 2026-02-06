@extends('layouts.app')

@section('title', 'Hasil Seleksi PMB')
@section('header', 'Pengumuman')

@push('css')
<link rel="stylesheet" href="{{ asset('css/user-pmb.css') }}">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endpush

@section('content')
<div class="announcement-container">
    @if(!$kartu)
        <div class="empty-state">
            <i class="fas fa-id-card-alt"></i>
            <h3 class="font-weight-bold text-dark">Belum Ada Riwayat Ujian</h3>
            <p class="text-muted">Anda belum memiliki kartu ujian. Pastikan Anda telah menyelesaikan pemberkasan dan verifikasi.</p>
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-4 px-4 py-2" style="border-radius: 10px;">Kembali ke Dashboard</a>
        </div>
    @elseif(!$kelulusan || !$kelulusan->is_published)
        <div class="empty-state">
            <i class="fas fa-hourglass-half"></i>
            <h3 class="font-weight-bold text-dark">Hasil Sedang Diproses</h3>
            <p class="text-muted">Pengumuman kelulusan belum diterbitkan oleh Admin. Silakan cek kembali halaman ini secara berkala.</p>
            <div class="mt-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    @else
        <canvas id="confetti-canvas"></canvas>
        <div class="result-card animate-fade-in-up">
            <!-- Header Section -->
            <div class="result-header {{ $kelulusan->status == 'lulus' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                <div class="status-icon status-icon-anim">
                    <i class="fas {{ $kelulusan->status == 'lulus' ? 'fa-check' : 'fa-times' }}"></i>
                </div>
                <h1 class="result-title animate-scale-in">{{ $kelulusan->status == 'lulus' ? 'LULUS' : 'TIDAK LULUS' }}</h1>
                <p class="result-subtitle">Hasil Seleksi Penerimaan Mahasiswa Baru</p>
            </div>

            <!-- Body Section -->
            <div class="result-body">
                <div class="text-center mb-4">
                    <h5 class="text-muted">Berdasarkan hasil seleksi ujian, kami menyatakan bahwa:</h5>
                </div>

                <div class="info-table">
                    <div class="info-row">
                        <span class="info-label">NOMOR PESERTA</span>
                        <span class="info-value">{{ $kartu->nomor_peserta }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NAMA LENGKAP</span>
                        <span class="info-value">{{ strtoupper($user->profile->nama_lengkap ?? $user->username) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">TAHUN AKADEMIK</span>
                        <span class="info-value">{{ $kelulusan->pengaturanUjian->tahun_akademik }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">GELOMBANG</span>
                        <span class="info-value">GELOMBANG {{ $kelulusan->pengaturanUjian->gelombang }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">SKOR UJIAN</span>
                        <span class="info-value h5 mb-0 text-dark">
                            @php
                                $nilai = $kelulusan->nilai;
                                echo fmod($nilai, 1) == 0 ? number_format($nilai, 0) : $nilai;
                            @endphp
                            / 100
                        </span>
                    </div>
                </div>

                @if($kelulusan->status == 'lulus')
                    <div class="action-box success">
                        <h4 class="font-weight-bold">Selamat! Anda Diterima</h4>
                        <p class="mb-0">Anda dinyatakan lolos sebagai calon mahasiswa Program Studi Teknik Radiologi Pencitraan. Langkah selanjutnya adalah melakukan pendaftaran ulang.</p>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfrNqA6bsQRFS121fA_B1eue02nzZkyQYmcxAfPhyYyCiCqyw/viewform" class="btn btn-success btn-action shadow-sm">
                            <i class="fas fa-file-signature mr-2"></i> PENDAFTARAN ULANG
                        </a>
                    </div>
                @else
                    <div class="action-box danger">
                        <h4 class="font-weight-bold">Jangan Patah Semangat</h4>
                        <p class="mb-0">Mohon maaf, Anda belum berhasil lolos pada seleksi kali ini. Terima kasih telah mengikuti proses seleksi kami.</p>
                        <a href="https://wa.me/628123456789" class="btn btn-danger btn-action shadow-sm">
                            <i class="fas fa-question-circle mr-2"></i> KONSULTASI HASIL
                        </a>
                    </div>
                @endif

                @if($kelulusan->catatan)
                    <div class="mt-4 p-3 rounded" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                        <small class="font-weight-bold text-warning-emphasis uppercase d-block mb-1">CATATAN PANITIA:</small>
                        <p class="mb-0 text-dark small">{{ $kelulusan->catatan }}</p>
                    </div>
                @endif
                
                <div class="text-center mt-5">
                    <p class="small text-muted italic">*Keputusan ini bersifat mutlak dan tidak dapat diganggu gugat.</p>
                    <button onclick="window.print()" class="btn btn-link btn-sm text-secondary">
                        <i class="fas fa-print mr-1"></i> Cetak Hasil Pengumuman
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
<script>
    @if($kelulusan && $kelulusan->status == 'lulus')
    window.addEventListener('load', function() {
        var end = Date.now() + (3 * 1000);
        var colors = ['#10b981', '#ffffff', '#0082CB'];

        (function frame() {
            confetti({
                particleCount: 3,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: colors
            });
            confetti({
                particleCount: 3,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: colors
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());

        // One big blast
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: colors
        });
    });
    @endif
</script>
@endpush
@endsection
