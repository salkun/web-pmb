@extends('layouts.app')

@section('title', 'Pengaturan Ujian')
@section('header', 'Pengaturan Jadwal Ujian')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush

@section('content')
<!-- Filter & Stats Row -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $ujian->total() }}</h4>
                <p>Total Jadwal</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $ujian->where('is_active', true)->count() }}</h4>
                <p>Jadwal Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon" style="background: rgba(135, 209, 230, 0.1); color: #0082CB;">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="stat-info">
                <h4>{{ \App\Models\KartuUjian::count() }}</h4>
                <p>Kartu Digenerate</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card ujian-card">
            <div class="card-header ujian-card-header">
                <h3>Daftar Jadwal Ujian</h3>
                <button class="btn btn-light btn-sm font-weight-bold" data-toggle="modal" data-target="#modalCreate" style="border-radius: 8px; padding: 0.5rem 1rem;">
                    <i class="fas fa-plus mr-1"></i> Tambah Jadwal
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>TAHUN AKADEMIK</th>
                                <th>GEL.</th>
                                <th>TANGGAL UJIAN</th>
                                <th>WAKTU</th>
                                <th>TEMPAT & RUANGAN</th>
                                <th>STATUS</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ujian as $u)
                                <tr>
                                    <td class="font-weight-bold">{{ $u->tahun_akademik }}</td>
                                    <td>
                                        <div class="badge badge-info px-2 py-1" style="border-radius: 4px;">{{ $u->gelombang }}</div>
                                    </td>
                                    <td>{{ $u->tanggal_ujian->format('d M Y') }}</td>
                                    <td>
                                        <span class="text-muted"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($u->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($u->waktu_selesai)->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $u->tempat_ujian }}</div>
                                        <small class="text-muted text-wrap d-block" style="max-width: 200px;">{{ $u->alamat_lengkap }}</small>
                                    </td>
                                    <td>
                                        @if($u->is_active)
                                            <span class="badge-status badge-active"><i class="fas fa-check-circle mr-1"></i> AKTIF</span>
                                        @else
                                            <span class="badge-status badge-inactive">NONAKTIF</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if(!$u->is_active)
                                                <form action="{{ route('admin.ujian.activate', $u->id) }}" method="POST">
                                                    @csrf
                                                    <button onclick="return confirm('Aktifkan jadwal ini? Jadwal lain akan otomatis nonaktif.')" class="btn-action bg-success text-white mx-1" title="Aktifkan">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button class="btn-action bg-info text-white btn-edit mx-1" 
                                                data-id="{{ $u->id }}"
                                                data-tahun="{{ $u->tahun_akademik }}"
                                                data-gel="{{ $u->gelombang }}"
                                                data-tanggal="{{ $u->tanggal_ujian->format('Y-m-d') }}"
                                                data-mulai="{{ \Carbon\Carbon::parse($u->waktu_mulai)->format('H:i') }}"
                                                data-selesai="{{ \Carbon\Carbon::parse($u->waktu_selesai)->format('H:i') }}"
                                                data-tempat="{{ $u->tempat_ujian }}"
                                                data-alamat="{{ $u->alamat_lengkap }}"
                                                data-kuota="{{ $u->kuota }}"
                                                data-toggle="modal" data-target="#modalEdit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.ujian.destroy', $u->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Hapus jadwal ini?')" class="btn-action bg-danger text-white mx-1" title="Hapus" {{ $u->is_active ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-times mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                            <p>Belum ada jadwal ujian yang terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($ujian->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $ujian->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Tambah Jadwal Ujian</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.ujian.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" placeholder="2025/2026" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gelombang</label>
                            <input type="number" name="gelombang" class="form-control" placeholder="1" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Ujian</label>
                        <input type="date" name="tanggal_ujian" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tempat Ujian</label>
                        <input type="text" name="tempat_ujian" class="form-control" placeholder="Kampus A, Ruang Serbaguna" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Alamat Lengkap Tempat Ujian</label>
                        <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Jl. Raya Utama No. 123..." required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Kuota Peserta</label>
                        <input type="number" name="kuota" class="form-control" value="0">
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Isi 0 jika tidak ada batasan kuota.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 8px; background: #0082CB; border: none;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0082CB;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Jadwal Ujian</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" id="edit_tahun" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gelombang</label>
                            <input type="number" name="gelombang" id="edit_gel" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Ujian</label>
                        <input type="date" name="tanggal_ujian" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="edit_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="edit_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tempat Ujian</label>
                        <input type="text" name="tempat_ujian" id="edit_tempat" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" id="edit_alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" id="edit_kuota" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 8px; background: #0082CB; border: none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        var tahun = $(this).data('tahun');
        var gel = $(this).data('gel');
        var tanggal = $(this).data('tanggal');
        var mulai = $(this).data('mulai');
        var selesai = $(this).data('selesai');
        var tempat = $(this).data('tempat');
        var alamat = $(this).data('alamat');
        var kuota = $(this).data('kuota');

        var updateUrl = "{{ route('admin.ujian.update', ':id') }}".replace(':id', id);
        $('#formEdit').attr('action', updateUrl);
        $('#edit_tahun').val(tahun);
        $('#edit_gel').val(gel);
        $('#edit_tanggal').val(tanggal);
        $('#edit_mulai').val(mulai);
        $('#edit_selesai').val(selesai);
        $('#edit_tempat').val(tempat);
        $('#edit_alamat').val(alamat);
        $('#edit_kuota').val(kuota);
    });
</script>
@endpush
