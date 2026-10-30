@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Inventaris Gudang')

@section('content')
<div class="container">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>

    <h1>Riwayat Transaksi Stok</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <p>
        <a class="button" href="{{ route('transactions.create') }}">
            + Tambah Transaksi
        </a>
        <a href="{{ route('inventory.index') }}">Lihat Inventaris</a>
    </p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
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
                        <td>{{ $transactions->firstItem() + $loop->index }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->product?->sku ?? '-' }}</td>
                        <td>{{ $transaction->product?->name ?? 'Produk tidak tersedia' }}</td>
                        <td>
                            @if ($transaction->type === 'in')
                                <span class="incoming">Barang Masuk</span>
                            @else
                                <span class="outgoing">Barang Keluar</span>
                            @endif
                        </td>
                        <td>{{ number_format($transaction->quantity) }} unit</td>
                        <td>{{ $transaction->user?->name ?? 'Pengguna tidak tersedia' }}</td>
                        <td>{{ $transaction->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Belum ada transaksi stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $transactions->links() }}
</div>
@endsection
