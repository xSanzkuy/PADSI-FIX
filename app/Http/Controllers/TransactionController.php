<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil input tanggal dari form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mengambil semua transaksi beserta relasi pegawai dan detail produknya
        $transactions = Transaction::with('details.product', 'pegawai');

        // Jika ada input tanggal, maka tambahkan filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $transactions = $transactions->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($startDate) {
            $transactions = $transactions->whereDate('tanggal', '>=', $startDate);
        } elseif ($endDate) {
            $transactions = $transactions->whereDate('tanggal', '<=', $endDate);
        }

        // Dapatkan hasil transaksi
        $transactions = $transactions->paginate(10);
        return view('transactions.index', compact('transactions', 'startDate', 'endDate'));
    }

    public function create()
    {
        // Mengambil data pegawai, produk, dan member untuk form transaksi
        $pegawai = Pegawai::all();
        $products = Product::all();
        $members = Member::all();
        return view('transactions.create', compact('pegawai', 'products', 'members'));
    }

    public function store(Request $request)
    {
        // Validasi input transaksi
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:members,id', // Validasi member_id jika ada
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data pegawai untuk membuat ID Transaksi custom
            $pegawai = Pegawai::findOrFail($request->pegawai_id);

            // Membuat ID Transaksi dengan format: nama pegawai + timestamp
            $pegawaiNameSlug = str_replace(' ', '_', strtoupper($pegawai->nama));
            $formattedDateTime = Carbon::now()->format('Ymd_His');
            $customTransactionId = "{$pegawaiNameSlug}_{$formattedDateTime}";

            // Inisialisasi transaksi baru dengan ID kustom
            $transaction = new Transaction();
            $transaction->id = $customTransactionId; // Set custom ID
            $transaction->tanggal = $request->input('tanggal');
            $transaction->pegawai_id = $request->pegawai_id;
            $transaction->telp_pelanggan = $request->telp_pelanggan; // Boleh null
            $transaction->nominal = $request->nominal;
            $transaction->member_id = $request->member_id;

            // Hitung total bayar berdasarkan item yang dibeli
            $totalBayar = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Cek apakah stok mencukupi
                if ($product->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi.");
                }

                $subtotal = $product->harga * $item['jumlah'];
                $totalBayar += $subtotal;
            }

            // Terapkan diskon berdasarkan tingkat member jika ada
            $member = null;
            if ($transaction->member_id) {
                $member = Member::findOrFail($transaction->member_id);
                $diskon = 0;

                // Menentukan diskon berdasarkan tingkat loyalitas member
                switch ($member->tingkat) {
                    case 'bronze': $diskon = 0.05; break;
                    case 'silver': $diskon = 0.10; break;
                    case 'gold': $diskon = 0.15; break;
                }

                // Mengurangi total bayar dengan diskon
                $totalBayar -= $totalBayar * $diskon;
            }

            // Cek apakah nominal cukup untuk total bayar
            if ($request->nominal < $totalBayar) {
                throw new \Exception("Nominal pembayaran tidak cukup untuk total transaksi.");
            }

            // Set nilai total bayar dan kembalian
            $transaction->total_bayar = $totalBayar;
            $transaction->kembalian = $transaction->nominal - $transaction->total_bayar;
            $transaction->save(); // Simpan transaksi

            // Simpan detail transaksi dan update stok produk
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->stok -= $item['jumlah'];
                $product->save();

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $product->harga,
                    'subtotal' => $product->harga * $item['jumlah'],
                ]);
            }

            // Menambah total transaksi dan cek upgrade tingkat loyalitas jika member terkait
            if ($member) {
                $member->addTransaction($totalBayar); // Menambah total transaksi dan cek upgrade tingkat
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function details($id)
    {
        // Mengambil data transaksi berdasarkan ID beserta relasi detail transaksi dan produk terkait
        $transaction = Transaction::with('details.product', 'pegawai')->findOrFail($id);
    
        return view('transactions.details', compact('transaction'));
    }

    public function edit($id)
    {
        // Mengambil data transaksi berdasarkan ID beserta relasi pegawai dan detail produknya
        $transaction = Transaction::with('details.product')->findOrFail($id);
        $transaction->tanggal = Carbon::parse($transaction->tanggal); // Pastikan tanggal dalam format Carbon
        $pegawai = Pegawai::all();
        $products = Product::all();
        $members = Member::all();

        return view('transactions.edit', compact('transaction', 'pegawai', 'products', 'members'));
    }
    

    public function update(Request $request, $id)
    {
        // Validasi input transaksi
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'member_id' => 'nullable|exists:members,id',
            'nominal' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // Mulai transaction untuk memastikan integritas data

        try {
            // Mengambil data transaksi
            $transaction = Transaction::findOrFail($id);

            // Kembalikan stok produk sebelumnya
            foreach ($transaction->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $product->stok += $detail->jumlah;
                $product->save();
            }

            // Update data transaksi
            $transaction->pegawai_id = $request->pegawai_id;
            $transaction->member_id = $request->member_id;
            $transaction->nominal = $request->nominal;

            // Hitung total bayar berdasarkan item yang dibeli
            $totalBayar = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Cek apakah stok mencukupi
                if ($product->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi.");
                }

                $subtotal = $product->harga * $item['jumlah'];
                $totalBayar += $subtotal;
            }

            // Terapkan diskon berdasarkan tingkat member jika ada
            $member = Member::find($request->member_id);
            if ($member) {
                $diskon = 0;
                switch ($member->tingkat) {
                    case 'bronze':
                        $diskon = 0.05; // 5% diskon untuk Bronze
                        break;
                    case 'silver':
                        $diskon = 0.10; // 10% diskon untuk Silver
                        break;
                    case 'gold':
                        $diskon = 0.15; // 15% diskon untuk Gold
                        break;
                }
                $totalBayar -= $totalBayar * $diskon;
            }

            if ($request->nominal < $totalBayar) {
                throw new \Exception("Uang tidak cukup untuk melakukan pembayaran.");
            }

            $transaction->total_bayar = $totalBayar;
            $transaction->kembalian = $transaction->nominal - $transaction->total_bayar;

            $transaction->save(); // Simpan transaksi

            // Hapus detail transaksi sebelumnya
            TransactionDetail::where('transaction_id', $transaction->id)->delete();

            // Simpan detail transaksi yang baru dan update stok produk
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->stok -= $item['jumlah'];
                $product->save();

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $product->harga,
                    'subtotal' => $product->harga * $item['jumlah'],
                ]);
            }

            // Menambah total transaksi dan cek upgrade tingkat loyalitas jika member terkait
            if ($member) {
                $member->addTransaction($totalBayar); // Menambah total transaksi dan cek upgrade tingkat
            }

            DB::commit(); // Commit transaction jika semua proses berhasil

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the print view for a specific transaction.
     */
    public function print($id)
    {
        // Mengambil data transaksi berdasarkan ID beserta relasi detail transaksi dan produk terkait
        $transaction = Transaction::with('details.product', 'pegawai', 'member')->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }
}
