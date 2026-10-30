@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container dashboard-page">
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <section class="welcome">
        <div>
            <p class="welcome-eyebrow">RINGKASAN OPERASIONAL</p>
            <h1>Selamat datang, {{ auth()->user()->name }}!</h1>
            <p>Pantau kondisi persediaan dan aktivitas gudang dari satu tempat.</p>
        </div>
        <div class="welcome-actions">
            <a class="button" href="{{ route('inventory.index') }}">Lihat Inventaris</a>
            <a class="button secondary" href="{{ route('transactions.create') }}">+ Transaksi Stok</a>
        </div>
    </section>

    <section class="cards" aria-label="Ringkasan inventaris">
        <article class="card">
            <p>Total Produk</p>
            <h2>{{ number_format($totalProducts) }}</h2>
            <small>Jenis produk terdaftar</small>
        </article>
        <article class="card">
            <p>Total Kategori</p>
            <h2>{{ number_format($totalCategories) }}</h2>
            <small>Kategori produk</small>
        </article>
        <article class="card">
            <p>Total Unit Stok</p>
            <h2>{{ number_format($totalStock) }}</h2>
            <small>Jumlah seluruh unit tersedia</small>
        </article>
        <article class="card">
            <p>Produk Stok Menipis</p>
            <h2>{{ number_format($lowStockCount) }}</h2>
            <small>Stok sama dengan atau di bawah batas minimum</small>
        </article>
    </section>

    <section class="panel dashboard-section">
        <div class="section-heading">
            <div>
                <h2>Produk dengan Stok Menipis</h2>
                <p>Produk yang perlu diperhatikan atau segera diisi ulang.</p>
            </div>
            <a class="button secondary" href="{{ route('inventory.index') }}">Lihat semua produk</a>
        </div>

        @if ($lowStockProducts->isEmpty())
            <p class="empty">Semua produk memiliki stok di atas batas minimum.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Stok Tersedia</th>
                            <th>Stok Minimum</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->sku }}</td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge">{{ $product->stock }}</span></td>
                                <td>{{ $product->minimum_stock }}</td>
                                <td><span class="status-low">Perlu restok</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="panel dashboard-section">
        <div class="section-heading">
            <div>
                <h2>Transaksi Stok Terbaru</h2>
                <p>Aktivitas barang masuk dan keluar yang terakhir dicatat.</p>
            </div>
            <a class="button secondary" href="{{ route('transactions.index') }}">Lihat riwayat</a>
        </div>

        @if ($recentTransactions->isEmpty())
            <p class="empty">Belum ada transaksi stok.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $transaction->product?->name ?? 'Produk tidak tersedia' }}</strong></td>
                                <td>
                                    @if ($transaction->type === 'in')
                                        <span class="status-in">Barang masuk</span>
                                    @else
                                        <span class="status-out">Barang keluar</span>
                                    @endif
                                </td>
                                <td>{{ number_format($transaction->quantity) }}</td>
                                <td>{{ $transaction->user?->name ?? 'Pengguna tidak tersedia' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
