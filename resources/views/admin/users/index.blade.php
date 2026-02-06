@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin-pmb.css') }}">
@endpush

@section('content')

<!-- Stats Row -->
<div class="row stats-row">
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $users->total() }}</div>
            <div class="stat-label">Total User</div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-value">{{ \App\Models\User::where('is_active', true)->count() }}</div>
            <div class="stat-label">User Aktif</div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-value">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
            <div class="stat-label">Administrator</div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card user-card">
    <div class="card-header user-card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#modalImport">
                <i class="fas fa-file-excel"></i> Import Excel/CSV
            </button>
            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="card-body">
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="filter-label">Cari User</label>
                        <input type="text" name="search" class="form-control" placeholder="No Pendaftaran atau nama..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="filter-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="all">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="filter-label">Tampilkan</label>
                        <select name="per_page" class="form-control" onchange="document.getElementById('filterForm').submit()">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 baris</option>
                            <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 baris</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 baris</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="filter-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No Pendaftaran</th>
                        <th>Role</th>
                        <th>Nama Lengkap</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td data-label="ID">{{ $user->id }}</td>
                            <td data-label="no_pendaftaran">
                                <strong>{{ $user->no_pendaftaran }}</strong>
                            </td>
                            <td data-label="Role">
                                @if($user->role == 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span class="badge badge-user">User</span>
                                @endif
                            </td>
                            <td data-label="Nama Lengkap">{{ $user->profile->nama_lengkap ?? '-' }}</td>
                            <td data-label="Status">
                                @if($user->is_active)
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <button class="btn btn-info btn-action btn-edit"
                                    data-id="{{ $user->id }}"
                                    data-no_pendaftaran="{{ $user->no_pendaftaran }}"
                                    data-role="{{ $user->role }}"
                                    data-active="{{ $user->is_active }}"
                                    data-toggle="modal" data-target="#modalEdit"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if(auth_id() != $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus user ini?')" 
                                            class="btn btn-danger btn-action"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <h4>Tidak Ada Data</h4>
                                    <p>Belum ada user yang terdaftar atau tidak ditemukan dengan filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>No Pendaftaran</label>
                        <input type="text" name="no_pendaftaran" class="form-control" required minlength="5">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap (Optional)</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Otomatis buat profil...">
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>No Pendaftaran</label>
                        <input type="text" name="no_pendaftaran" id="edit_no_pendaftaran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="edit_active" name="is_active" value="1">
                        <label class="form-check-label" for="edit_active">Akun Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import User dari CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Format CSV:</strong><br>
                        No Pendaftaran, Password, Role, Nama Lengkap<br>
                        <br>
                        Contoh:<br>
                        <code>123456, rahasia, user, Budi Santoso</code>
                    </div>
                    <div class="form-group">
                        <label>Pilih File CSV/Excel</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" required accept=".csv, .txt">
                            <label class="custom-file-label">Pilih file...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
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
        var no_pendaftaran = $(this).data('no_pendaftaran');
        var role = $(this).data('role');
        var active = $(this).data('active');

        // Use Laravel route helper
        var updateUrl = "{{ route('admin.users.update', ':id') }}".replace(':id', id);
        $('#formEdit').attr('action', updateUrl);
        $('#edit_no_pendaftaran').val(no_pendaftaran);
        $('#edit_role').val(role);
        
        if (active) {
            $('#edit_active').prop('checked', true);
        } else {
             $('#edit_active').prop('checked', false);
        }
    });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endpush
