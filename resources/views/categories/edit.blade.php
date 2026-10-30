@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<h1>Edit Kategori</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Nama Kategori</label><br>
            <input type="text" name="name"
                   value="{{ old('name', $category->name) }}" required>
        </p>

        <p>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description', $category->description) }}</textarea>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </form>
@endsection
