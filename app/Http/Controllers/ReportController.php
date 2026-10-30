<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $transactionsQuery = StockTransaction::query()
            ->with(['product', 'user']);

        if ($startDate) {
            $transactionsQuery->where(
                'created_at',
                '>=',
                $startDate . ' 00:00:00'
            );
        }

        if ($endDate) {
            $transactionsQuery->where(
                'created_at',
                '<',
                date('Y-m-d', strtotime($endDate . ' +1 day')) . ' 00:00:00'
            );
        }

        $totalTransactions = (clone $transactionsQuery)->count();

        $totalIncoming = (clone $transactionsQuery)
            ->where('type', 'in')
            ->sum('quantity');

        $totalOutgoing = (clone $transactionsQuery)
            ->where('type', 'out')
            ->sum('quantity');

        $transactions = $transactionsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalProducts = $products->count();

        $totalStock = $products->sum('stock');

        $lowStockCount = $products->filter(function ($product) {
            return $product->stock <= $product->minimum_stock;
        })->count();

        $stockValue = $products->sum(function ($product) {
            return $product->stock * (float) $product->price;
        });

        $totalCategories = Category::count();

        return view('reports.index', compact(
            'products',
            'transactions',
            'startDate',
            'endDate',
            'totalProducts',
            'totalStock',
            'lowStockCount',
            'stockValue',
            'totalCategories',
            'totalTransactions',
            'totalIncoming',
            'totalOutgoing'
        ));
    }

    public function exportStock(): StreamedResponse
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return response()->streamDownload(function () use ($products) {
            $output = fopen('php://output', 'w');

            // BOM agar karakter UTF-8 terbaca baik oleh Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'SKU',
                'Nama Produk',
                'Kategori',
                'Harga',
                'Stok',
                'Stok Minimum',
                'Status Stok',
                'Nilai Persediaan',
            ], ';');

            foreach ($products as $product) {
                $status = $product->stock <= $product->minimum_stock
                    ? 'Stok Menipis'
                    : 'Aman';

                fputcsv($output, [
                    $product->sku,
                    $product->name,
                    $product->category?->name ?? '-',
                    $product->price,
                    $product->stock,
                    $product->minimum_stock,
                    $status,
                    $product->stock * (float) $product->price,
                ], ';');
            }

            fclose($output);
        }, 'laporan-stok-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportTransactions(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $query = StockTransaction::with(['product', 'user'])
            ->orderBy('created_at');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        }

        if ($endDate) {
            $query->where(
                'created_at',
                '<',
                date('Y-m-d', strtotime($endDate . ' +1 day')) . ' 00:00:00'
            );
        }

        $transactions = $query->get();

        return response()->streamDownload(function () use ($transactions) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Waktu',
                'SKU',
                'Nama Produk',
                'Jenis Transaksi',
                'Jumlah',
                'Petugas',
                'Catatan',
            ], ';');

            foreach ($transactions as $transaction) {
                fputcsv($output, [
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->product?->sku ?? '-',
                    $transaction->product?->name ?? '-',
                    $transaction->type === 'in'
                        ? 'Barang Masuk'
                        : 'Barang Keluar',
                    $transaction->quantity,
                    $transaction->user?->name ?? '-',
                    $transaction->notes ?? '-',
                ], ';');
            }

            fclose($output);
        }, 'laporan-transaksi-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
