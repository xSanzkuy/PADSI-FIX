<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Member;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk, transaksi, dan revenue
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = TransactionDetail::sum('subtotal');

        // Produk dengan transaksi terbanyak
        $topProduct = TransactionDetail::select('product_id')
            ->with('product')
            ->groupBy('product_id')
            ->orderByRaw('SUM(jumlah) DESC')
            ->first();
        $topProductName = $topProduct ? $topProduct->product->nama_produk : 'No Data';

        // Top 3 Member berdasarkan jumlah transaksi
        $topMembers = Member::withCount(['transactions'])
            ->orderBy('transactions_count', 'desc')
            ->limit(3)
            ->get();

        // Data untuk grafik transaksi per hari
        $transactionData = Transaction::selectRaw('DATE(tanggal) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $transactionDates = $transactionData->pluck('date')->toArray();
        $transactionTotals = $transactionData->pluck('total')->toArray();

        // Data pendapatan berdasarkan produk
        $productRevenueData = TransactionDetail::select('product_id')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->get();

        $productNames = Product::whereIn('id', $productRevenueData->pluck('product_id'))->pluck('nama_produk')->toArray();
        $productRevenue = $productRevenueData->pluck('total_revenue')->toArray();

        // Transaksi terbaru
        $recentTransactions = Transaction::orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalTransactions',
            'totalRevenue',
            'topProductName',
            'topMembers',
            'transactionDates',
            'transactionTotals',
            'recentTransactions',
            'productNames',
            'productRevenue' // Menambahkan data untuk grafik pie
        ));
    }
}
