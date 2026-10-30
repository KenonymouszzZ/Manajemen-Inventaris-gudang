@extends('layouts.app')

@section('title', 'Registrasi - Inventaris Gudang')

@section('content')
<div class="auth-container">
<h1>Inventaris Gudang</h1>
    <h2>Buat Akun Staff</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div>
            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}" required maxlength="255">
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                   required minlength="8">
        </div>

        <div>
            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" type="password"
                   name="password_confirmation" required minlength="8">
        </div>

        <button type="submit">Daftar sebagai Staff</button>
    </form>

    <p>Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </p>
</div>
@endsection
