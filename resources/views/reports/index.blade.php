@extends('layouts.app')

@section('title', 'Laporan Inventaris Gudang')

@section('content')
<div class="container">

    <p>
        <a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a>
    </p>

    <h1>Laporan Inventaris Gudang</h1>
    <p>Ringkasan stok dan riwayat transaksi gudang.</p>

    @if ($errors->any())
        <div class="panel error">
            <strong>Periksa filter laporan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter tanggal --}}
    <section class="panel">
        <h3>Filter Riwayat Transaksi</h3>

        <form method="GET" action="{{ route('reports.index') }}">
            <div class="toolbar">
                <label>
                    Dari tanggal
                    <input type="date" name="start_date"
                           value="{{ $startDate }}">
                </label>

                <label>
                    Sampai tanggal
                    <input type="date" name="end_date"
                           value="{{ $endDate }}">
                </label>

                <button type="submit">Terapkan Filter</button>

                <a class="button secondary"
                   href="{{ route('reports.index') }}">Reset</a>
            </div>
        </form>

        <div class="toolbar">
            <a class="button" href="{{ route('reports.export-stock') }}">
                Unduh Laporan Stok CSV
            </a>

            <a class="button" href="{{ route('reports.export-transactions', array_filter([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])) }}">
                Unduh Transaksi CSV
            </a>
        </div>

        <small>
            Filter tanggal berlaku untuk ringkasan transaksi dan riwayat
            transaksi. Laporan stok menampilkan kondisi stok saat ini.
        </small>
    </section>

    {{-- Ringkasan --}}
    <section class="cards">
        <div class="card">
            <p>Total Produk</p>
            <h2>{{ number_format($totalProducts) }}</h2>
        </div>

        <div class="card">
            <p>Total Kategori</p>
            <h2>{{ number_format($totalCategories) }}</h2>
        </div>

        <div class="card">
            <p>Total Unit Stok</p>
            <h2>{{ number_format($totalStock) }}</h2>
        </div>

        <div class="card">
            <p>Stok Menipis</p>
            <h2>{{ number_format($lowStockCount) }}</h2>
        </div>

        <div class="card">
            <p>Total Barang Masuk</p>
            <h2>{{ number_format($totalIncoming) }}</h2>
            <small>Unit pada periode terpilih</small>
        </div>

        <div class="card">
            <p>Total Barang Keluar</p>
            <h2>{{ number_format($totalOutgoing) }}</h2>
            <small>Unit pada periode terpilih</small>
        </div>

        <div class="card">
            <p>Jumlah Transaksi</p>
            <h2>{{ number_format($totalTransactions) }}</h2>
            <small>Baris transaksi pada periode terpilih</small>
        </div>

        <div class="card">
            <p>Nilai Persediaan</p>
            <h2>Rp {{ number_format($stockValue, 0, ',', '.') }}</h2>
            <small>Stok saat ini × harga produk</small>
        </div>
    </section>

    {{-- Laporan stok --}}
    <section class="panel">
        <h2>Laporan Stok Saat Ini</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Stok Minimum</th>
                        <th>Status</th>
                        <th>Nilai Persediaan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </td>
                            <td>{{ number_format($product->stock) }}</td>
                            <td>{{ number_format($product->minimum_stock) }}</td>
                            <td>
                                @if ($product->stock <= $product->minimum_stock)
                                    <span class="low">Stok Menipis</span>
                                @else
                                    <span class="safe">Aman</span>
                                @endif
                            </td>
                            <td>
                                Rp {{ number_format(
                                    $product->stock * (float) $product->price,
                                    0, ',', '.'
                                ) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Riwayat transaksi --}}
    <section class="panel">
        <h2>Riwayat Transaksi</h2>

        @if ($startDate || $endDate)
            <p>
                Periode:
                {{ $startDate ?: 'Awal' }}
                sampai
                {{ $endDate ?: 'Sekarang' }}
            </p>
        @endif

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>SKU</th>
                        <th>Produk</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $transaction->product?->sku ?? '-' }}</td>
                            <td>{{ $transaction->product?->name ?? '-' }}</td>
                            <td>
                                @if ($transaction->type === 'in')
                                    <span class="safe">Barang Masuk</span>
                                @else
                                    <span class="low">Barang Keluar</span>
                                @endif
                            </td>
                            <td>{{ number_format($transaction->quantity) }} unit</td>
                            <td>{{ $transaction->user?->name ?? '-' }}</td>
                            <td>{{ $transaction->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $transactions->links() }}
        </div>
    </section>

</div>
@endsection
