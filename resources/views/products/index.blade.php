@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="container">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>

    <h1>Manajemen Produk</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif

    <p><a href="{{ route('categories.index') }}">Lihat Kategori</a></p>

    @if (auth()->user()->role === 'admin')
        <a class="primary" href="{{ route('products.create') }}">+ Tambah Produk</a>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                    @if (auth()->user()->role === 'admin')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->minimum_stock }}</td>

                        @if (auth()->user()->role === 'admin')
                            <td>
                                <div class="actions">
                                    <a class="primary"
                                       href="{{ route('products.edit', $product) }}">Edit</a>

                                    <form method="POST"
                                          action="{{ route('products.destroy', $product) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p>{{ $products->links() }}</p>
</div>
@endsection
