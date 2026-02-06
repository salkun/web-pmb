@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Profil Saya')

@push('css')
<link rel="stylesheet" href="{{ asset('css/user-pmb.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <!-- Alert Banner -->
    @if(!$profile->is_complete)
        <div class="alert-banner d-flex align-items-center">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content flex-grow-1">
                <h5>Profil Belum Lengkap</h5>
                <p>Silakan lengkapi semua data diri Anda untuk dapat melanjutkan ke proses pemberkasan dan pendaftaran ujian.</p>
            </div>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header-card">
        <div class="profile-header-content">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    @if($profile->foto_profil)
                        <img src="{{ Storage::url($profile->foto_profil) }}" alt="Foto Profil">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
            </div>
            <div class="profile-info flex-grow-1">
                <h2>{{ $profile->nama_lengkap ?: auth_user()['no_pendaftaran'] }}</h2>
                <div class="profile-meta">
                    <div class="profile-meta-item">
                        <i class="fas fa-id-card"></i>
                        <span>{{ auth_user()['no_pendaftaran'] }}</span>
                    </div>
                    <div class="profile-meta-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $profile->program_studi }}</span>
                    </div>
                    @if($profile->email_aktif && $profile->email_aktif != '-')
                        <div class="profile-meta-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $profile->email_aktif }}</span>
                        </div>
                    @endif
                </div>
                <div class="completion-badge {{ $profile->is_complete ? 'complete' : '' }}">
                    <i class="fas {{ $profile->is_complete ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $profile->is_complete ? 'Profil Lengkap' : 'Profil Belum Lengkap' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Global Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
            <div class="d-flex">
                <div class="mr-3">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Terdapat Kesalahan!</h5>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Personal Information -->
        <div class="form-card">
            <div class="form-card-header">
                <h3>
                    <div class="section-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    Data Pribadi
                </h3>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" 
                                name="nama_lengkap" 
                                class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                value="{{ old('nama_lengkap', $profile->nama_lengkap) }}" 
                                placeholder="Masukkan nama lengkap sesuai KTP"
                                required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Jenis Kelamin <span class="required">*</span>
                            </label>
                            <div class="radio-group @error('jenis_kelamin') is-invalid @enderror">
                                <div class="radio-option">
                                    <input type="radio" 
                                        name="jenis_kelamin" 
                                        id="laki" 
                                        value="L" 
                                        {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                    <label for="laki">Laki-laki</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" 
                                        name="jenis_kelamin" 
                                        id="perempuan" 
                                        value="P" 
                                        {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                    <label for="perempuan">Perempuan</label>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Tempat Lahir <span class="required">*</span>
                            </label>
                            <input type="text" 
                                name="tempat_lahir" 
                                class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                value="{{ old('tempat_lahir', $profile->tempat_lahir) }}" 
                                placeholder="Contoh: Jakarta"
                                required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Tanggal Lahir <span class="required">*</span>
                            </label>
                            <input type="date" 
                                name="tanggal_lahir" 
                                class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                value="{{ old('tanggal_lahir', $profile->tanggal_lahir ? $profile->tanggal_lahir->format('Y-m-d') : '') }}" 
                                required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education Information -->
        <div class="form-card">
            <div class="form-card-header">
                <h3>
                    <div class="section-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    Data Pendidikan
                </h3>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Asal Sekolah <span class="required">*</span>
                            </label>
                            <input type="text" 
                                name="asal_sekolah" 
                                class="form-control @error('asal_sekolah') is-invalid @enderror" 
                                value="{{ old('asal_sekolah', $profile->asal_sekolah) }}" 
                                placeholder="Contoh: SMA Negeri 1 Jakarta"
                                required>
                            @error('asal_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Tahun Kelulusan <span class="required">*</span>
                            </label>
                            <input type="number" 
                                name="tahun_kelulusan" 
                                class="form-control @error('tahun_kelulusan') is-invalid @enderror" 
                                value="{{ old('tahun_kelulusan', $profile->tahun_kelulusan) }}" 
                                placeholder="{{ date('Y') }}"
                                required 
                                min="2000" 
                                max="{{ date('Y') }}">
                            @error('tahun_kelulusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Program Studi</label>
                            <input type="text" 
                                class="form-control" 
                                value="{{ $profile->program_studi }}" 
                                disabled>
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                <span>Program studi tidak dapat diubah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="form-card">
            <div class="form-card-header">
                <h3>
                    <div class="section-icon">
                        <i class="fas fa-address-book"></i>
                    </div>
                    Informasi Kontak
                </h3>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Email Aktif <span class="required">*</span>
                            </label>
                            <input type="email" 
                                name="email_aktif" 
                                class="form-control @error('email_aktif') is-invalid @enderror" 
                                value="{{ old('email_aktif', $profile->email_aktif) }}" 
                                placeholder="contoh@email.com"
                                required>
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                <span>Gunakan email yang aktif untuk notifikasi</span>
                            </div>
                            @error('email_aktif')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                No HP WhatsApp Aktif <span class="required">*</span>
                            </label>
                            <input type="text" 
                                name="no_hp_aktif" 
                                class="form-control @error('no_hp_aktif') is-invalid @enderror" 
                                value="{{ old('no_hp_aktif', $profile->no_hp_aktif) }}" 
                                placeholder="08xxxxxxxxxx"
                                required>
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                <span>Nomor WhatsApp untuk komunikasi</span>
                            </div>
                            @error('no_hp_aktif')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                Alamat Lengkap <span class="required">*</span>
                            </label>
                            <textarea name="alamat" 
                                class="form-control @error('alamat') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Masukkan alamat lengkap sesuai KTP"
                                required>{{ old('alamat', $profile->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Upload -->
        <div class="form-card">
            <div class="form-card-header">
                <h3>
                    <div class="section-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    Foto Profil
                </h3>
            </div>
            <div class="form-card-body">
                <div class="photo-upload-area" id="photoUploadArea">
                    <input type="file" 
                        id="foto_profil" 
                        name="foto_profil" 
                        accept="image/*" 
                        style="display: none;">
                    
                    <div id="photoPreview" style="{{ $profile->foto_profil ? '' : 'display: none;' }}">
                        <div class="photo-preview">
                            <img src="{{ $profile->foto_profil ? Storage::url($profile->foto_profil) : '#' }}" 
                                alt="Preview Foto" 
                                id="previewImage">
                        </div>
                        <button type="button" class="btn-choose-file" onclick="document.getElementById('foto_profil').click()">
                            <i class="fas fa-sync-alt"></i> Ganti Foto
                        </button>
                    </div>

                    <div id="uploadPlaceholder" class="upload-placeholder" style="{{ $profile->foto_profil ? 'display: none;' : '' }}">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>Upload Foto Profil</h4>
                        <p>Format: JPG/PNG | Ukuran: 3x4 atau 4x6 | Max: 5MB</p>
                        <button type="button" class="btn-choose-file" onclick="document.getElementById('foto_profil').click()">
                            <i class="fas fa-upload"></i> Pilih Foto
                        </button>
                    </div>
                </div>
                @error('foto_profil')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-card">
            <div class="action-buttons">
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Profil
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Photo upload handler
    document.getElementById('foto_profil').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('photoPreview').style.display = 'block';
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('photoUploadArea').classList.add('has-image');
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Form validation feedback
    @error('foto_profil')
        Swal.fire({
            icon: 'error',
            title: 'Gagal Upload Foto',
            text: '{{ $message }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @enderror
</script>
@endpush
