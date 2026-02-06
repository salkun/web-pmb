@extends('layouts.guest')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="card">
    <div class="card-body register-card-body">
        <p class="login-box-msg">Daftar akun baru</p>

        <form action="{{ route('register.post') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="no_pendaftaran" class="form-control @error('no_pendaftaran') is-invalid @enderror" placeholder="No. Pendaftaran" value="{{ old('no_pendaftaran') }}" required minlength="5" maxlength="50">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('no_pendaftaran')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required minlength="8">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Tulis ulang password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                        <label for="agreeTerms">
                        Saya setuju dengan <a href="#">ketentuan</a>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ route('login') }}" class="text-center mt-3 d-block">Saya sudah punya akun</a>
    </div>
    <!-- /.form-box -->
</div>
@endsection
