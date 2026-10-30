<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $totalStock = Product::sum('stock');

        $lowStockProducts = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->orderBy('stock')->take(5)->get();

        $lowStockCount = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();

        $recentTransactions = StockTransaction::with([
            'product',
            'user',
        ])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockCount',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}
