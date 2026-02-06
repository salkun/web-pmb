@extends('layouts.app')

@section('title', 'Verifikasi Pemberkasan')
@section('header', 'Verifikasi Pemberkasan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush

@section('content')
<!-- Filter Search -->
<div class="filter-card shadow-sm">
    <form method="GET" action="{{ route('admin.berkas.index') }}">
        <div class="row align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-right-0" style="border-radius: 8px 0 0 8px; padding-left: 1.25rem;">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" name="search" class="form-control form-control-custom border-left-0" style="border-radius: 0 8px 8px 0;" placeholder="Cari berdasarkan nama mahasiswa atau username..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-block py-2 font-weight-bold" type="submit" style="border-radius: 8px; background: #0082CB; border: none;">
                    Cari Data
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Main Content Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card berkas-card">
            <div class="card-header berkas-card-header">
                <h3>Daftar Verifikasi Berkas Mahasiswa</h3>
                <span class="badge badge-light px-3 py-2 text-dark font-weight-bold" style="border-radius: 8px;">TOTAL: {{ $students->total() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th width="60">NO</th>
                                <th>MAHASISWA</th>
                                <th>USERNAME</th>
                                <th class="text-center">PENDING</th>
                                <th class="text-center">VERIFIED</th>
                                <th class="text-center">REJECTED</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $st)
                                <tr>
                                    <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($st->profile && $st->profile->foto_profil)
                                                <img src="{{ Storage::url($st->profile->foto_profil) }}" class="student-avatar mr-3">
                                            @else
                                                <div class="avatar-placeholder mr-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $st->profile->nama_lengkap ?? 'No Profile Name' }}</div>
                                                <small class="text-muted"><i class="fas fa-id-card mr-1"></i> {{ $st->profile->nik ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="px-2 py-1 bg-light rounded" style="color: #0082CB;">{{ $st->username }}</code>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-count {{ $st->pending_count > 0 ? 'badge-count-pending' : '' }}">
                                            {{ $st->pending_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-count {{ $st->verified_count > 0 ? 'badge-count-verified' : '' }}">
                                            {{ $st->verified_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-count {{ $st->rejected_count > 0 ? 'badge-count-rejected' : '' }}">
                                            {{ $st->rejected_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.berkas.show', $st->id) }}" class="btn-periksa">
                                            <i class="fas fa-file-signature"></i> Periksa Berkas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open mb-3" style="font-size: 3.5rem; opacity: 0.3;"></i>
                                            <p class="font-weight-bold">Tidak ada data mahasiswa ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($students->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $students->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
