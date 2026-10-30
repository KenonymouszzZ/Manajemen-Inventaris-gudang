@extends('layouts.app')

@section('title', 'Data Kategori')

@section('content')
<div class="container">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>

    <h1>Manajemen Kategori</h1>

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

    @if (auth()->user()->role === 'admin')
        <a class="primary" href="{{ route('categories.create') }}">+ Tambah Kategori</a>
    @endif

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk</th>
                @if (auth()->user()->role === 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?: '-' }}</td>
                    <td>{{ $category->products_count }}</td>

                    @if (auth()->user()->role === 'admin')
                        <td>
                            <div class="actions">
                                <a class="primary"
                                   href="{{ route('categories.edit', $category) }}">Edit</a>

                                <form method="POST"
                                      action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                    <td colspan="5" class="muted">Belum ada kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p>{{ $categories->links() }}</p>
</div>
@endsection
