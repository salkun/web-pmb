@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Anda lupa password Anda? Di sini Anda dapat meminta reset password baru.</p>

        <form action="{{ route('password.reset.link') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="no_pendaftaran" class="form-control" placeholder="No. Pendaftaran" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Minta password baru</button>
                </div>
            </div>
        </form>

        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">Login</a>
        </p>
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Daftar akun baru</a>
        </p>
    </div>
</div>
@endsection
