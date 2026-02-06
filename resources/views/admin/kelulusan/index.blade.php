@extends('layouts.app')

@section('title', 'Input Kelulusan')
@section('header', 'Input Hasil Kelulusan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush
 
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Peserta Ujian</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.kelulusan.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari No Peserta">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-custom table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Peserta</th>
                            <th>Nama</th>
                            <th>Jadwal Ujian</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peserta as $p)
                            <tr>
                                <td>{{ $p->nomor_peserta }}</td>
                                <td>{{ optional($p->user)->profile->nama_lengkap ?? optional($p->user)->username ?? 'N/A' }}</td>
                                <td>
                                    {{ $p->pengaturanUjian->tahun_akademik }} <br>
                                    <small>{{ $p->pengaturanUjian->tanggal_ujian->format('d M Y') }}</small>
                                </td>
                                <td>
                                    @php
                                        $nilai = optional(optional($p->user)->kelulusan)->nilai;
                                        echo $nilai ? (fmod($nilai, 1) == 0 ? number_format($nilai, 0) : $nilai) : '-';
                                    @endphp
                                </td>
                                <td>
                                    @if(optional(optional($p->user)->kelulusan)->status == 'lulus')
                                        <span class="badge badge-success">LULUS</span>
                                    @elseif(optional(optional($p->user)->kelulusan)->status == 'tidak_lulus')
                                        <span class="badge badge-danger">TIDAK LULUS</span>
                                    @else
                                        <span class="badge badge-secondary">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-input" 
                                        data-userid="{{ $p->user_id }}"
                                        data-ujianid="{{ $p->pengaturan_ujian_id }}"
                                        data-nama="{{ optional($p->user)->profile->nama_lengkap ?? optional($p->user)->username ?? 'N/A' }}"
                                        data-nilai="{{ optional(optional($p->user)->kelulusan)->nilai ?? '' }}"
                                        data-status="{{ optional(optional($p->user)->kelulusan)->status ?? 'lulus' }}"
                                        data-catatan="{{ optional(optional($p->user)->kelulusan)->catatan ?? '' }}"
                                        data-toggle="modal" data-target="#modalInput">
                                        <i class="fas fa-edit"></i> Input Nilai
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada peserta ujian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $peserta->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Input -->
<div class="modal fade" id="modalInput" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Kelulusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kelulusan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="input_userid">
                <input type="hidden" name="pengaturan_ujian_id" id="input_ujianid">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Peserta</label>
                        <input type="text" class="form-control" id="input_nama" disabled>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nilai Ujian (0-100)</label>
                            <input type="number" name="nilai" id="input_nilai" class="form-control" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Status Kelulusan</label>
                            <select name="status" id="input_status" class="form-control" required>
                                <option value="lulus">LULUS</option>
                                <option value="tidak_lulus">TIDAK LULUS</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Optional)</label>
                        <textarea name="catatan" id="input_catatan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.btn-input').on('click', function() {
        var userid = $(this).data('userid');
        var ujianid = $(this).data('ujianid');
        var nama = $(this).data('nama');
        var nilai = $(this).data('nilai');
        var status = $(this).data('status');
        var catatan = $(this).data('catatan');

        $('#input_userid').val(userid);
        $('#input_ujianid').val(ujianid);
        $('#input_nama').val(nama);
        $('#input_nilai').val(nilai);
        $('#input_status').val(status);
        $('#input_catatan').val(catatan);
    });
</script>
@endpush
