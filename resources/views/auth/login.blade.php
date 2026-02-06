@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<form action="{{ route('login.post') }}" method="post" id="loginForm">
    @csrf
    
    <div class="form-group">
        <label class="form-label" for="no_pendaftaran">No. Pendaftaran</label>
        <div class="input-group-custom">
            <i class="fas fa-user-circle"></i>
            <input type="text" name="no_pendaftaran" id="no_pendaftaran" class="form-control-custom" placeholder="Masukkan No. Pendaftaran" required autofocus>
        </div>
    </div>

    <div class="form-group mb-2">
        <label class="form-label" for="password">Password</label>
        <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" class="form-control-custom" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn-auth">
        <span>Masuk Sekarang</span>
        <i class="fas fa-arrow-right"></i>
    </button>
</form>

@if(session('success_link')) 
    <div class="alert alert-info mt-4 border-0" style="background: rgba(0, 130, 203, 0.08); color: #0082CB; border-radius: 12px; font-size: 0.8rem; padding: 1rem;">
        <i class="fas fa-link mr-1"></i> <strong>Reset Link:</strong><br>
        <a href="{{ session('success_link') }}" style="text-decoration: underline; font-weight: 700; color: #011E41;">Klik di sini untuk reset password</a>
    </div>
@endif

@endsection

@push('scripts')
<script>
    $('#loginForm').on('submit', function() {
        const btn = $(this).find('.btn-auth');
        btn.html('<i class="fas fa-circle-notch fa-spin"></i><span>Memproses...</span>');
        btn.css('pointer-events', 'none');
        btn.css('opacity', '0.8');
    });
</script>
@endpush
