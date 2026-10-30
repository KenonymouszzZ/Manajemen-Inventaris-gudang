@extends('layouts.app')

@section('title', 'Tambah Transaksi Stok')

@section('content')
<div class="container">
    <p><a href="{{ route('transactions.index') }}">← Kembali ke Transaksi</a></p>

    <h1>Tambah Transaksi Stok</h1>

    @if ($errors->any())
        <div class="error">
            <p>Periksa kembali data berikut:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($products->isEmpty())
        <p class="muted">Belum ada produk. Tambahkan produk terlebih dahulu.</p>
        <a href="{{ route('inventory.index') }}">Buka Inventaris</a>
    @else
        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <label for="product_id">Produk</label>
            <select name="product_id" id="product_id" required>
                <option value="">-- Pilih produk --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        @selected(old('product_id') == $product->id)>
                        {{ $product->sku }} - {{ $product->name }}
                        (Stok: {{ $product->stock }})
                    </option>
                @endforeach
            </select>

            <label for="type">Jenis Transaksi</label>
            <select name="type" id="type" required>
                <option value="">-- Pilih jenis --</option>
                <option value="in" @selected(old('type') === 'in')>
                    Barang Masuk
                </option>
                <option value="out" @selected(old('type') === 'out')>
                    Barang Keluar
                </option>
            </select>

            <label for="quantity">Jumlah Unit</label>
            <input type="number"
                   name="quantity"
                   id="quantity"
                   min="1"
                   step="1"
                   value="{{ old('quantity') }}"
                   required>

            <label for="notes">Catatan (opsional)</label>
            <textarea name="notes" id="notes" rows="3"
                maxlength="1000">{{ old('notes') }}</textarea>

            <button type="submit">Simpan Transaksi</button>
        </form>
    @endif
</div>
@endsection
