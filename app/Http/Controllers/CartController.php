<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    // Method untuk menampilkan halaman keranjang
    public function index()
    {
        // Mengambil item-item keranjang berdasarkan user yang sedang login
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        
        // Menampilkan halaman keranjang dengan data cartItems
        return view('cart.index', compact('cartItems'));
    }

    // Method untuk menambahkan produk ke dalam keranjang
  public function addToCart(Request $request, $id)
{
    // Mencari produk berdasarkan ID
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    // Mencari item keranjang yang sudah ada
    $cart = Cart::where('user_id', auth()->id())
                ->where('product_id', $id)
                ->first();

    if ($cart) {
        // Jika sudah ada, tambahkan kuantitas
        $cart->quantity += 1;
        $cart->total_price = $cart->quantity * $product->harga;
        $cart->save();
    } else {
        // Jika belum ada, tambahkan item baru
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'quantity' => 1,
            'total_price' => $product->harga,
        ]);
    }

    // Hitung jumlah item di keranjang
    $cartCount = Cart::where('user_id', auth()->id())->count();

    // Mengembalikan respons JSON dengan properti success
    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil ditambahkan ke keranjang.',
        'cartCount' => $cartCount, // Opsional, untuk memperbarui jumlah keranjang di front-end
    ]);
}


    // Method untuk menghapus item dari keranjang
    public function removeFromCart($id)
    {
        // Mencari item keranjang berdasarkan ID dan user yang sedang login
        $cartItem = Cart::where('user_id', auth()->id())->where('id', $id)->first();

        // Jika item keranjang ditemukan, hapus dari database
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
        } else {
            // Jika item tidak ditemukan di keranjang, tampilkan pesan error
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
        }
    }

    // Method untuk mengupdate jumlah produk di keranjang
    public function updateQuantity(Request $request, $id)
    {
        // Mencari item keranjang berdasarkan ID
        $cartItem = Cart::where('user_id', auth()->id())->where('id', $id)->first();

        // Jika item keranjang ditemukan
        if ($cartItem) {
            // Validasi kuantitas, pastikan lebih besar dari 0
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
            
            // Update kuantitas dan harga total berdasarkan kuantitas baru
            $cartItem->quantity = $request->quantity;
            $cartItem->total_price = $cartItem->quantity * $cartItem->product->harga;
            $cartItem->save(); // Simpan perubahan
            
            return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
        } else {
            // Jika item tidak ditemukan di keranjang
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
        }
    }
}
