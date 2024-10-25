<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $penjualan;

    public function __construct()
    {
        $this->penjualan = new Penjualan();
    }

    public function index()
    {
        return view('dashboard');
    }

    public function income()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $totalIncome = Penjualan::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->sum('total_harga');
        return response()->json($totalIncome);
    }

    public function salesCount()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $totalSales = Penjualan::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count();
        return response()->json($totalSales);
    }

    public function transactionsByYear(Request $request)
    {
        $year = $request->input('tahun');
        $salesCount = $this->penjualan->whereYear('created_at', $year)->count();

        $data = [
            'sales' => $salesCount,
        ];

        return response()->json($data);
    }

    public function incomeByProductService(Request $request)
    {
        $year = $request->input('tahun');
        $month = $request->input('bulan') ?? date('n');

        $productIncome = $this->penjualan->incomeByProduk($year, $month);
        $serviceIncome = $this->penjualan->incomeByJasa($year, $month);

        $productTotal = (int) $productIncome->first()->Total ?? 0;
        $serviceTotal = (int) $serviceIncome->first()->Total ?? 0;

        $data = [
            'product' => $productTotal,
            'service' => $serviceTotal,
        ];

        return response()->json($data);
    }
}
