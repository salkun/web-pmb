@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Anda hanya selangkah lagi dari password baru Anda, pulihkan password Anda sekarang.</p>

        <form action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password Baru" required minlength="8">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Ubah password</button>
                </div>
            </div>
        </form>

        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</div>
@endsection
