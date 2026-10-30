@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<h1>Tambah Produk</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red">{{ $error }}</p>
        @endforeach
    @endif

    @if ($categories->isEmpty())
        <p>Tambahkan kategori terlebih dahulu sebelum membuat produk.</p>
        <a href="{{ route('categories.create') }}">Tambah Kategori</a>
    @else
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            <p>
                <label>Kategori</label><br>
                <select name="category_id" required>
                    <option value="">-- Pilih kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                <label>SKU / Kode Produk</label><br>
                <input name="sku" value="{{ old('sku') }}" required>
            </p>

            <p>
                <label>Nama Produk</label><br>
                <input name="name" value="{{ old('name') }}" required>
            </p>

            <p>
                <label>Deskripsi</label><br>
                <textarea name="description">{{ old('description') }}</textarea>
            </p>

            <p>
                <label>Harga (Rp)</label><br>
                <input type="number" name="price" min="0" step="0.01"
                       value="{{ old('price', '0') }}" required>
            </p>

            <p>
                <label>Stok Awal</label><br>
                <input type="number" name="stock" min="0" step="1"
                       value="{{ old('stock', '0') }}" required>
            </p>

            <p>
                <label>Stok Minimum</label><br>
                <input type="number" name="minimum_stock" min="0" step="1"
                       value="{{ old('minimum_stock', '0') }}" required>
            </p>

            <button type="submit">Simpan Produk</button>
            <a href="{{ route('inventory.index') }}">Batal</a>
        </form>
    @endif
@endsection
