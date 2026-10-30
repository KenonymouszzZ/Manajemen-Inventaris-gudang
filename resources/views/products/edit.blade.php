@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<h1>Edit Produk</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Kategori</label><br>
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>SKU / Kode Produk</label><br>
            <input name="sku" value="{{ old('sku', $product->sku) }}" required>
        </p>

        <p>
            <label>Nama Produk</label><br>
            <input name="name" value="{{ old('name', $product->name) }}" required>
        </p>

        <p>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
        </p>

        <p>
            <label>Harga (Rp)</label><br>
            <input type="number" name="price" min="0" step="0.01"
                   value="{{ old('price', $product->price) }}" required>
        </p>

        <p>
            <label>Stok</label><br>
            <input type="number" name="stock" min="0" step="1"
                   value="{{ old('stock', $product->stock) }}" required>
        </p>

        <p>
            <label>Stok Minimum</label><br>
            <input type="number" name="minimum_stock" min="0" step="1"
                   value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('inventory.index') }}">Batal</a>
    </form>
@endsection
