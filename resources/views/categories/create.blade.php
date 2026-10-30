@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<h1>Tambah Kategori</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <p>
            <label>Nama Kategori</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </p>

        <p>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
        </p>

        <button type="submit">Simpan Kategori</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </form>
@endsection
