@extends('layouts.app')

@section('title', 'Periksa Berkas Mahasiswa')
@section('header', 'Detail Verifikasi Berkas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Student Info Card -->
        <div class="card profile-card">
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($student->profile && $student->profile->foto_profil)
                        <img class="img-fluid rounded-circle shadow-sm"
                             src="{{ Storage::url($student->profile->foto_profil) }}"
                             alt="User profile picture"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 120px; height: 120px; border: 4px solid #fff;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <h4 class="mt-3 font-weight-bold text-dark">{{ $student->profile->nama_lengkap ?? 'Belum Isi Profil' }}</h4>
                    <span class="badge badge-info px-3 py-2" style="border-radius: 30px; background: rgba(0, 130, 203, 0.1); color: #0082CB;">{{ $student->username }}</span>
                </div>

                <div class="info-list mb-4">
                    <div class="info-item">
                        <span class="info-label">Program Studi</span>
                        <span class="info-value">{{ $student->profile->program_studi ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Asal Sekolah</span>
                        <span class="info-value text-wrap" style="max-width: 150px;">{{ $student->profile->asal_sekolah ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $student->profile->email_aktif ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIK</span>
                        <span class="info-value">{{ $student->profile->nik ?? '-' }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.berkas.index') }}" class="btn btn-outline-secondary btn-block font-weight-bold py-2" style="border-radius: 10px;">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card doc-card">
            <div class="card-header doc-card-header">
                <h3>Daftar Dokumen Pendukung</h3>
                <div class="small font-weight-bold text-muted">TOTAL BERKAS: {{ count($berkas) }}</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table custom-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>JENIS BERKAS</th>
                                <th>FILE</th>
                                <th>STATUS</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($berkas as $bk)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $bk->jenisBerkas->nama_berkas }}</div>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $bk->uploaded_at->format('d M Y, H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ Storage::url($bk->file_path) }}" target="_blank" class="btn btn-link btn-sm text-info font-weight-bold p-0">
                                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Dokumen
                                        </a>
                                    </td>
                                    <td>
                                        @if($bk->status == 'pending')
                                            <span class="badge badge-warning px-3 py-2" style="border-radius: 6px;">WAITING</span>
                                        @elseif($bk->status == 'verified')
                                            <span class="badge badge-success px-3 py-2" style="border-radius: 6px;">VERIFIED</span>
                                        @else
                                            <span class="badge badge-danger px-3 py-2" style="border-radius: 6px;">REJECTED</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($bk->status == 'pending')
                                            <button class="btn btn-primary btn-sm btn-action-verify btn-verifikasi shadow-sm" 
                                                style="background: #0082CB; border: none;"
                                                data-id="{{ $bk->id }}" 
                                                data-jenis="{{ $bk->jenisBerkas->nama_berkas }}"
                                                data-file="{{ Storage::url($bk->file_path) }}"
                                                data-toggle="modal" data-target="#modalVerifikasi">
                                                <i class="fas fa-check-circle mr-1"></i> VERIFIKASI
                                            </button>
                                        @else
                                            <button class="btn btn-outline-info btn-sm btn-action-verify btn-verifikasi"
                                                data-id="{{ $bk->id }}" 
                                                data-jenis="{{ $bk->jenisBerkas->nama_berkas }}"
                                                data-file="{{ Storage::url($bk->file_path) }}"
                                                data-toggle="modal" data-target="#modalVerifikasi">
                                                EDIT STATUS
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-signature mr-2"></i> Verifikasi Berkas: <span id="modalJenis" class="text-info"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formVerifikasi" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="font-weight-bold text-muted small"><i class="fas fa-eye mr-1"></i> PRATINJAU DOKUMEN</label>
                            <div class="embed-responsive shadow-sm" style="height: 650px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; overflow: hidden;">
                                <iframe id="previewFrame" class="embed-responsive-item" src=""></iframe>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-4 rounded-lg h-100" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                                <label class="font-weight-bold text-dark mb-4"><i class="fas fa-gavel mr-1"></i> KEPUTUSAN VERIFIKATOR</label>
                                
                                <div class="custom-control custom-radio custom-control-lg mb-4">
                                    <input class="custom-control-input" type="radio" id="terima" name="status" value="verified" checked>
                                    <label for="terima" class="custom-control-label text-success font-weight-bold cursor-pointer h5">TERIMA BERKAS</label>
                                    <p class="text-muted small mt-1 ml-1">Dokumen sesuai dengan persyaratan yang ditentukan.</p>
                                </div>
                                <hr>
                                <div class="custom-control custom-radio custom-control-lg mt-4">
                                    <input class="custom-control-input" type="radio" id="tolak" name="status" value="rejected">
                                    <label for="tolak" class="custom-control-label text-danger font-weight-bold cursor-pointer h5">TOLAK BERKAS</label>
                                    <p class="text-muted small mt-1 ml-1">Dokumen tidak sesuai atau membutuhkan perbaikan.</p>
                                </div>

                                <div class="form-group mt-4" id="catatanGroup" style="display: none;">
                                    <label class="font-weight-bold text-dark small">CATATAN / ALASAN PENOLAKAN <span class="text-danger">*</span></label>
                                    <textarea name="catatan_admin" class="form-control" rows="6" style="border-radius: 10px;" placeholder="Mohon berikan penjelasan detail alasan penolakan agar mahasiswa tahu apa yang harus diperbaiki..."></textarea>
                                </div>
                                
                                <div class="alert alert-warning mt-4 border-0 p-3" style="background: rgba(245, 158, 11, 0.1); color: #b45309; border-radius: 10px;">
                                    <small class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> PENTING:</small><br>
                                    <small>Pastikan Anda telah memeriksa seluruh aspek dokumen sebelum memberikan keputusan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal" style="border-radius: 10px;">BATAL</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-5" style="border-radius: 10px; background: #0082CB; border: none;">SIMPAN KEPUTUSAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.btn-verifikasi').on('click', function() {
            var id = $(this).data('id');
            var jenis = $(this).data('jenis');
            var fileUrl = $(this).data('file');

            $('#modalJenis').text(jenis);
            $('#previewFrame').attr('src', fileUrl);
            
            // Fix action URL logic
            var updateUrl = "{{ url('/admin/berkas') }}/" + id + "/verify";
            $('#formVerifikasi').attr('action', updateUrl);
            
            // Reset form
            $('#terima').prop('checked', true);
            $('#catatanGroup').hide();
            $('textarea[name="catatan_admin"]').prop('required', false);
        });

        $('input[name="status"]').on('change', function() {
             if ($('#tolak').is(':checked')) {
                 $('#catatanGroup').slideDown();
                 $('textarea[name="catatan_admin"]').prop('required', true);
             } else {
                 $('#catatanGroup').slideUp();
                 $('textarea[name="catatan_admin"]').prop('required', false);
             }
        });
    });
</script>
@endpush
