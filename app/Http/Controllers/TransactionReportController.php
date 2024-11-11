<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF; // Pastikan menggunakan alias PDF

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil input tanggal dari form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $memberLevel = $request->input('member_level'); // Menambahkan filter tingkat member
    
        // Mengambil semua transaksi beserta relasi pegawai, detail produk, dan member
        $transactions = Transaction::with('details.product', 'pegawai', 'member');
    
        // Jika ada input tanggal, tambahkan filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $transactions = $transactions->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($startDate) {
            $transactions = $transactions->whereDate('tanggal', '>=', $startDate);
        } elseif ($endDate) {
            $transactions = $transactions->whereDate('tanggal', '<=', $endDate);
        }
    
        // Jika ada filter tingkat member, tambahkan filter ini
        if ($memberLevel) {
            $transactions = $transactions->whereHas('member', function($query) use ($memberLevel) {
                $query->where('tingkat', $memberLevel);
            });
        }
    
        // Dapatkan hasil transaksi dengan paginasi
        $transactions = $transactions->paginate(10);
    
        return view('reports.index', compact('transactions', 'startDate', 'endDate', 'memberLevel'));
    }
    
    // Fungsi untuk export PDF
    public function exportPDF(Request $request)
    {
        // Mengambil input tanggal dari form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil semua transaksi beserta relasi pegawai dan detail produknya
        $transactions = Transaction::with('details.product', 'pegawai');

        // Jika ada input tanggal, tambahkan filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $transactions = $transactions->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($startDate) {
            $transactions = $transactions->whereDate('tanggal', '>=', $startDate);
        } elseif ($endDate) {
            $transactions = $transactions->whereDate('tanggal', '<=', $endDate);
        }

        // Dapatkan hasil transaksi
        $transactions = $transactions->get();

        // Memuat view untuk PDF dan mengirimkan data transaksi
        $pdf = PDF::loadView('reports.laporan_pdf', compact('transactions', 'startDate', 'endDate'));

        // Mengunduh file PDF
        return $pdf->download('laporan-transaksi.pdf');
    }
}
