@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('header', 'Data Pendaftar')

@push('css')
<style>
    .student-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .student-card-header {
        background: white;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .table-custom {
        width: 100%;
        margin-bottom: 0;
    }
    .table-custom thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-top: none;
        padding: 15px;
    }
    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .badge-complete {
        background-color: #d4edda;
        color: #155724;
        font-weight: 500;
    }
    .badge-incomplete {
        background-color: #fff3cd;
        color: #856404;
        font-weight: 500;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="card student-card">
    <div class="student-card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">List Profil Pendaftar</h3>
        <div class="card-tools">
            <a href="{{ route('admin.students.export', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Form -->
        <div class="filter-section">
            <form action="{{ route('admin.students.index') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">CARI PENDAFTAR</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Nama, No Pendaftaran, Sekolah..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">STATUS DATA</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="all">Semua Status</option>
                            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Lengkap</option>
                            <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Belum Lengkap</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold">TAMPILKAN</label>
                        <select name="limit" class="form-control" onchange="this.form.submit()">
                            <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10 baris</option>
                            <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50 baris</option>
                            <option value="100" {{ request('limit') == '100' ? 'selected' : '' }}>100 baris</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">&nbsp;</label>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>Asal Sekolah</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>
                            <span class="font-weight-bold">{{ $student->no_pendaftaran }}</span>
                        </td>
                        <td>{{ $student->profile->nama_lengkap ?? '-' }}</td>
                        <td>{{ $student->profile->asal_sekolah ?? '-' }}</td>
                        <td>{{ $student->profile->no_hp_aktif ?? '-' }}</td>
                        <td>
                            @if($student->profile && $student->profile->is_complete)
                                <span class="badge badge-complete">Lengkap</span>
                            @else
                                <span class="badge badge-incomplete">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-primary btn-action" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-info btn-action" title="Edit Profil">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <h4>Data Tidak Ditemukan</h4>
                                <p>Tidak ada data pendaftar yang sesuai dengan kriteria pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($students->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} pendaftar
        </div>
        <div>
            {{ $students->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
