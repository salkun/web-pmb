@extends('layouts.app')

@section('title', 'Pemberkasan')
@section('header', 'Pemberkasan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/user-pmb.css') }}">
@endpush

@section('content')
<div class="berkas-container">
    <!-- Progress Summary -->
    <div class="progress-card">
        <div class="progress-header">
            <div class="progress-info">
                <h5>Progres Kelengkapan Berkas</h5>
                <p class="text-muted text-sm mb-0">Lengkapi semua berkas wajib untuk verifikasi akun Anda.</p>
            </div>
            <div class="progress-percentage">
                {{ number_format($progress, 0) }}%
            </div>
        </div>
        <div class="progress-wrapper">
            <div class="progress-bar-custom {{ $progress == 100 ? 'bg-success' : ($progress > 50 ? 'bg-warning' : 'bg-danger') }}" 
                 style="width: {{ $progress }}%"></div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-info-circle text-primary"></i>
            <small class="text-muted">Hanya berkas wajib yang dihitung dalam persentase progres.</small>
        </div>
    </div>

    <div class="row">
        @foreach($jenis_berkas as $jb)
            @php
                $uploaded = isset($berkas[$jb->id]) ? $berkas[$jb->id] : null;
                $status = $uploaded ? $uploaded->status : 'belum_upload';
                
                $icon = 'fa-file-alt';
                $status_text = 'Belum Upload';
                $status_class = 'status-empty';
                $icon_bg = '#f1f5f9';
                $icon_color = '#64748b';

                if ($status == 'pending') {
                    $icon = 'fa-clock';
                    $status_text = 'Menunggu';
                    $status_class = 'status-pending';
                    $icon_bg = '#fff7ed';
                    $icon_color = '#f59e0b';
                } elseif ($status == 'verified') {
                    $icon = 'fa-check-circle';
                    $status_text = 'Verified';
                    $status_class = 'status-verified';
                    $icon_bg = '#ecfdf5';
                    $icon_color = '#10b981';
                } elseif ($status == 'rejected') {
                    $icon = 'fa-times-circle';
                    $status_text = 'Ditolak';
                    $status_class = 'status-rejected';
                    $icon_bg = '#fef2f2';
                    $icon_color = '#ef4444';
                }
            @endphp

            <div class="col-md-6 mb-4">
                <div class="file-card {{ $status == 'verified' ? 'verified' : '' }}">
                    <div class="file-card-header">
                        <div class="file-type-icon" style="background: {{ $icon_bg }}; color: {{ $icon_color }};">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <span class="status-badge {{ $status_class }}">
                            {{ $status_text }}
                        </span>
                    </div>
                    <div class="file-card-body">
                        <h4 class="file-name">
                            {{ $jb->nama_berkas }}
                            @if($jb->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </h4>
                        <p class="file-description">
                            {{ $jb->deskripsi ?: 'Silakan upload scan berkas murni ' . $jb->nama_berkas . ' dalam format PDF atau Gambar.' }}
                        </p>

                        @if($jb->kode == 'SURAT_PERNYATAAN_SEHAT')
                            <div class="special-instruction-box p-3 mb-4" style="background: #e0f2fe; border: 1px dashed #0284c7; border-radius: 8px;">
                                <div class="d-flex mb-2">
                                    <i class="fas fa-file-medical text-primary mt-1 mr-2"></i>
                                    <strong class="text-primary" style="font-size: 0.95rem;">Instruksi Khusus:</strong>
                                </div>
                                <p class="small text-dark mb-2" style="line-height: 1.4;">
                                    Silakan unduh 2 dokumen berikut sebagai persyaratan tes kesehatan:
                                </p>
                                <ul class="small text-dark pl-3 mb-3" style="line-height: 1.4;">
                                    <li><a href="https://drive.google.com/file/d/1Q61yhFrLIVTyTXbDjgmuyVjoCP52Y0xV/view" target="_blank" class="btn btn-primary btn-sm btn-block shadow-sm">
                                    <i class="fab fa-google-drive mr-1"></i> Unduh Format Surat Pengantar Test Kesehatan</a></li><br>
                                    <li><a href="https://drive.google.com/file/d/1hm2RzfG2EpK33S_COTKIZoyvG0fR0I2c/view" target="_blank" class="btn btn-primary btn-sm btn-block shadow-sm">
                                    <i class="fab fa-google-drive mr-1"></i> Unduh Format Surat Pernyataan Keaslian Hasil Uji Kesehatan</a></li>
                                </ul>
                            </div>
                        @endif

                        @if($uploaded)
                            <div class="active-file-info">
                                <div class="active-file-icon">
                                    <i class="fas {{ str_contains($uploaded->file_original_name, '.pdf') ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                                </div>
                                <div class="active-file-details">
                                    <p class="active-file-name">{{ $uploaded->file_original_name }}</p>
                                    <p class="active-file-date">Diambil pada {{ $uploaded->uploaded_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="active-file-action">
                                    <a href="{{ Storage::url($uploaded->file_path) }}" target="_blank" class="text-primary" title="Lihat Berkas">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>

                            @if($status == 'rejected' && $uploaded->catatan_admin)
                                <div class="alert alert-danger border-0 p-3 mb-3" style="border-radius: 12px; background: #fff1f2;">
                                    <div class="d-flex gap-2">
                                        <i class="fas fa-exclamation-circle mt-1"></i>
                                        <div>
                                            <strong class="d-block mb-1">Alasan Penolakan:</strong>
                                            <span class="text-sm">{{ $uploaded->catatan_admin }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="mt-auto">
                            @if($status == 'belum_upload' || $status == 'rejected' || $status == 'pending')
                                <button type="button" class="btn btn-upload {{ $status == 'belum_upload' ? 'btn-upload-primary' : 'btn-outline-primary' }}" 
                                        data-toggle="modal" data-target="#uploadModal{{ $jb->id }}">
                                    <i class="fas {{ $uploaded ? 'fa-sync-alt' : 'fa-cloud-upload-alt' }}"></i>
                                    {{ $uploaded ? 'Update Berkas' : 'Upload Sekarang' }}
                                </button>
                            @else
                                <div class="btn btn-upload btn-light text-success cursor-default" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                    <i class="fas fa-check-double"></i> Berkas Terverifikasi
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Upload -->
            <div class="modal fade" id="uploadModal{{ $jb->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload {{ $jb->nama_berkas }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('user.berkas.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="jenis_berkas_id" value="{{ $jb->id }}">
                            <div class="modal-body">
                                <p class="text-muted text-sm mb-4">Pastikan dokumen terbaca dengan jelas dan tidak buram.</p>
                                
                                <div class="dropzone-area" onclick="document.getElementById('file{{ $jb->id }}').click()">
                                    <input type="file" name="file" id="file{{ $jb->id }}" class="d-none" required accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="dropzone-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="dropzone-text">
                                        <h4 id="fileNameLabel{{ $jb->id }}">Pilih atau Seret File</h4>
                                        <p>PDF, JPG, atau PNG (Maks. 5MB)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4 bg-primary border-0">Mulai Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    @foreach($jenis_berkas as $jb)
    document.getElementById('file{{ $jb->id }}').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih atau Seret File';
        document.getElementById('fileNameLabel{{ $jb->id }}').innerText = fileName;
        this.closest('.dropzone-area').style.borderColor = '#0082CB';
        this.closest('.dropzone-area').style.background = '#f0f9ff';
    });
    @endforeach
</script>
@endpush
