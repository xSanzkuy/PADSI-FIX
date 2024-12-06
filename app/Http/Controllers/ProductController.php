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

        // Mengambil data produk berdasarkan pencarian dengan pagination
        $products = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', '%' . $search . '%');
        })->paginate(10); // Menggunakan pagination

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
        'nama_produk' => [
            'required', 
            'string', 
            'max:255',
            function ($attribute, $value, $fail) {
                // Cek apakah produk dengan nama yang sama sudah ada (termasuk yang terhapus)
                $exists = Product::withTrashed()->where('nama_produk', $value)->exists();
                if ($exists) {
                    $fail('Nama produk sudah digunakan. Silakan pilih nama lain.');
                }
            }
        ],
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
            'nama_produk' => $request->input('nama_produk'),
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
        // Temukan produk terlebih dahulu
        $product = Product::findOrFail($id);

        // Validasi input (abaikan validasi nama_produk jika tidak berubah)
        $request->validate([
            'nama_produk' => 'required|string|max:255|unique:products,nama_produk,' . $product->id, // Abaikan nama_produk yang sama pada produk ini
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $data = $request->only(['nama_produk', 'stok', 'harga']);

            // Update gambar jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                }
                // Simpan gambar baru
                $data['gambar'] = $request->file('gambar')->store('products', 'public');
            }

            // Update produk
            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            \Log::error('Error saat memperbarui produk: ' . $e->getMessage());
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
    
            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus.']);
        } catch (Exception $e) {
            \Log::error("Error menghapus produk: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }
    }
    

}


