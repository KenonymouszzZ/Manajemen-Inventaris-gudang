<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockTransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi stok.
     */
    public function index(): View
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan form transaksi stok.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.create', compact('products'));
    }

    /**
     * Menyimpan transaksi dan memperbarui stok produk.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'type' => [
                'required',
                'in:in,out',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // Pastikan pengguna sudah login.
        $userId = Auth::id();

        if ($userId === null) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        DB::transaction(function () use ($validated, $userId) {
            // Mengunci baris produk selama transaksi berlangsung.
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];
            $type = $validated['type'];

            // Cegah stok menjadi negatif.
            if ($type === 'out' && $product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi. '
                        . 'Stok tersedia: '
                        . $product->stock
                        . ' unit.',
                ]);
            }

            // Perbarui stok berdasarkan jenis transaksi.
            if ($type === 'in') {
                $product->stock += $quantity;
            } else {
                $product->stock -= $quantity;
            }

            $product->save();

            // Catat transaksi berdasarkan pengguna yang login.
            StockTransaction::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with(
                'success',
                'Transaksi berhasil disimpan dan stok diperbarui.'
            );
    }
}