<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil semua transaksi beserta relasi pegawai dan detail produknya
        $transactions = Transaction::with('details.product', 'pegawai')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Mengambil data pegawai dan produk untuk form transaksi
        $pegawai = Pegawai::all();
        $products = Product::all();
        return view('transactions.create', compact('pegawai', 'products'));
    }

    public function store(Request $request)
    {
        // Validasi input transaksi
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'telp_pelanggan' => 'nullable|string|max:15',
            'nominal' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // Mulai transaction untuk memastikan integritas data

        try {
            // Inisialisasi transaksi baru
            $transaction = new Transaction();
            $transaction->tanggal = Carbon::now();
            $transaction->pegawai_id = $request->pegawai_id;
            $transaction->telp_pelanggan = $request->telp_pelanggan;
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

            DB::commit(); // Commit transaction jika semua proses berhasil

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error

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

        return view('transactions.edit', compact('transaction', 'pegawai', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input transaksi
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'telp_pelanggan' => 'nullable|string|max:15',
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
            $transaction->telp_pelanggan = $request->telp_pelanggan;
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

            DB::commit(); // Commit transaction jika semua proses berhasil

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            // Mengembalikan stok produk
            foreach ($transaction->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $product->stok += $detail->jumlah;
                $product->save();
            }

            // Hapus detail transaksi
            TransactionDetail::where('transaction_id', $transaction->id)->delete();

            // Hapus transaksi
            $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transactions.index')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
