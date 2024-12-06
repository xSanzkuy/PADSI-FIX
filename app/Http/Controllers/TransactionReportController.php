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
            if ($memberLevel === 'N/A') {
                // Filter transaksi tanpa member
                $transactions = $transactions->whereDoesntHave('member');
            } else {
                // Filter transaksi berdasarkan tingkat member
                $transactions = $transactions->whereHas('member', function ($query) use ($memberLevel) {
                    $query->where('tingkat', $memberLevel);
                });
            }
        }        
    
        // Dapatkan hasil transaksi dengan paginasi
        $transactions = $transactions->paginate(10);
    
        return view('reports.index', compact('transactions', 'startDate', 'endDate', 'memberLevel'));
    }
    
    // Fungsi untuk export PDF
    public function exportPDF(Request $request)
    {
        // Mengambil input filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $memberLevel = $request->input('member_level'); // Tambahkan filter tingkat member
    
        // Query transaksi dengan relasi pegawai, detail produk, dan member
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
            $transactions = $transactions->whereHas('member', function ($query) use ($memberLevel) {
                $query->where('tingkat', $memberLevel);
            });
        }
    
        // Ambil semua transaksi yang sudah difilter
        $transactions = $transactions->get();
    
        // Memuat view untuk PDF dan mengirimkan data transaksi beserta filter
        $pdf = PDF::loadView('reports.laporan_pdf', compact('transactions', 'startDate', 'endDate', 'memberLevel'));
    
        // Mengunduh file PDF
        return $pdf->download('laporan-transaksi.pdf');
    }
}    
