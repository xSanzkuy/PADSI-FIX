<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Get date inputs from form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get all transactions with related employee and product details
        $transactions = Transaction::with('details.product', 'pegawai');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $transactions = $transactions->whereBetween('tanggal', [$startDate, $endDate]);
        } elseif ($startDate) {
            $transactions = $transactions->whereDate('tanggal', '>=', $startDate);
        } elseif ($endDate) {
            $transactions = $transactions->whereDate('tanggal', '<=', $endDate);
        }

        // Paginate transactions
        $transactions = $transactions->paginate(10);
        return view('transactions.index', compact('transactions', 'startDate', 'endDate'));
    }

    public function create()
    {
        // Get employees, products, and members for transaction form
        $pegawai = Pegawai::all();
        $products = Product::all();
        $members = Member::all();

        // Get cart items for the logged-in user
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        
        // Calculate total price from all items in the cart
        $total = $cartItems->sum('total_price');

        return view('transactions.create', compact('pegawai', 'products', 'members', 'cartItems', 'total'));
    }

    public function store(Request $request)
    {
        // Validate transaction inputs
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:members,id', // Validate member_id if present
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Fetch employee data to create a custom Transaction ID
            $pegawai = Pegawai::findOrFail($request->pegawai_id);

            // Create a custom Transaction ID: employee name + timestamp
            $pegawaiNameSlug = str_replace(' ', '_', strtoupper($pegawai->nama));
            $formattedDateTime = Carbon::now()->format('Ymd_His');
            $customTransactionId = "{$pegawaiNameSlug}_{$formattedDateTime}";

            // Initialize a new transaction with a custom ID
            $transaction = new Transaction();
            $transaction->id = $customTransactionId;
            $transaction->tanggal = $request->input('tanggal');
            $transaction->pegawai_id = $request->pegawai_id;
            $transaction->telp_pelanggan = $request->telp_pelanggan; // Optional
            $transaction->nominal = $request->nominal;
            $transaction->member_id = $request->member_id;

            // Calculate the total payment based on items purchased
            $totalBayar = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check if stock is sufficient
                if ($product->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi.");
                }

                $subtotal = $product->harga * $item['jumlah'];
                $totalBayar += $subtotal;
            }

            // Apply discount based on member level if available
            $member = null;
            if ($transaction->member_id) {
                $member = Member::findOrFail($transaction->member_id);
                $diskon = 0;

                // Set discount based on loyalty level
                switch ($member->tingkat) {
                    case 'bronze': $diskon = 0.05; break;
                    case 'silver': $diskon = 0.10; break;
                    case 'gold': $diskon = 0.15; break;
                }

                // Deduct discount from the total payment
                $totalBayar -= $totalBayar * $diskon;
            }

            // Check if nominal payment is enough
            if ($request->nominal < $totalBayar) {
                throw new \Exception("Nominal pembayaran tidak cukup untuk total transaksi.");
            }

            // Set the total payment and change
            $transaction->total_bayar = $totalBayar;
            $transaction->kembalian = $transaction->nominal - $transaction->total_bayar;
            $transaction->save();

            // Save transaction details and update product stock
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

            // Increase total transactions and check for loyalty level upgrade if member is linked
            if ($member) {
                $member->addTransaction($totalBayar);
            }

            // Clear cart items after successful transaction
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function details($id)
    {
        // Get transaction details with related products and employee
        $transaction = Transaction::with('details.product', 'pegawai')->findOrFail($id);
    
        return view('transactions.details', compact('transaction'));
    }

    public function edit($id)
    {
        // Get transaction with related employee and product details
        $transaction = Transaction::with('details.product')->findOrFail($id);
        $transaction->tanggal = Carbon::parse($transaction->tanggal);
        $pegawai = Pegawai::all();
        $products = Product::all();
        $members = Member::all();

        return view('transactions.edit', compact('transaction', 'pegawai', 'products', 'members'));
    }

    public function update(Request $request, $id)
    {
        // Validate transaction inputs
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'member_id' => 'nullable|exists:members,id',
            'nominal' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            // Return stock of previous products
            foreach ($transaction->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $product->stok += $detail->jumlah;
                $product->save();
            }

            // Update transaction data
            $transaction->pegawai_id = $request->pegawai_id;
            $transaction->member_id = $request->member_id;
            $transaction->nominal = $request->nominal;

            // Recalculate the total payment based on new items
            $totalBayar = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi.");
                }

                $subtotal = $product->harga * $item['jumlah'];
                $totalBayar += $subtotal;
            }

            // Apply discount if member level is provided
            $member = Member::find($request->member_id);
            if ($member) {
                $diskon = 0;
                switch ($member->tingkat) {
                    case 'bronze': $diskon = 0.05; break;
                    case 'silver': $diskon = 0.10; break;
                    case 'gold': $diskon = 0.15; break;
                }
                $totalBayar -= $totalBayar * $diskon;
            }

            if ($request->nominal < $totalBayar) {
                throw new \Exception("Uang tidak cukup untuk melakukan pembayaran.");
            }

            $transaction->total_bayar = $totalBayar;
            $transaction->kembalian = $transaction->nominal - $transaction->total_bayar;

            $transaction->save();

            // Delete previous transaction details
            TransactionDetail::where('transaction_id', $transaction->id)->delete();

            // Save new transaction details and update product stock
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

            if ($member) {
                $member->addTransaction($totalBayar);
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function print($id)
    {
        // Get transaction with related product, employee, and member details
        $transaction = Transaction::with('details.product', 'pegawai', 'member')->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }
}
