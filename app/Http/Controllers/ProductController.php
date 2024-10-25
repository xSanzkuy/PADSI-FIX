<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Menerima input pencarian (search)
        $search = $request->input('search');

        // Mengambil data produk berdasarkan pencarian
        $products = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', '%' . $search . '%');
        })->get();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_product' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Upload gambar produk
            if ($request->hasFile('gambar')) {
                $imagePath = $request->file('gambar')->store('products', 'public');
            } else {
                throw new \Exception("Gambar tidak ditemukan saat proses upload");
            }

            // Buat produk baru
            Product::create([
                'nama_produk' => $request->input('nama_product'),
                'stok' => $request->input('stok'),
                'harga' => $request->input('harga'),
                'gambar' => $imagePath,
            ]);

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan produk: ' . $e->getMessage());
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan produk, silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);
            $data = $request->only(['nama_produk', 'stok', 'harga']);
            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                }
                // Store new image
                $data['gambar'] = $request->file('gambar')->store('products', 'public');
            }
            $product->update($data);
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui data produk, silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('products.index')->withErrors('Terjadi kesalahan saat menghapus produk, silakan coba lagi.');
        }
    }
}
