<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use App\Models\Pegawai;
use App\Models\Product;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Pastikan Anda memiliki Member, Pegawai, dan Product di database
        $members = Member::all();
        $pegawai = Pegawai::all();
        $products = Product::all();

        // Buat beberapa transaksi untuk setiap member
        foreach ($members as $member) {
            // Transaksi untuk member ini
            $transaction = Transaction::create([
                'tanggal' => now(),
                'pegawai_id' => $pegawai->random()->id, // Ambil pegawai secara acak
                'member_id' => $member->id,
                'telp_pelanggan' => '085912345678',
                'total_bayar' => 0, // Akan dihitung di bawah
                'nominal' => 100000,
                'kembalian' => 0,
            ]);

            // Tambahkan detail transaksi dengan produk acak
            $totalBayar = 0;

            for ($i = 0; $i < rand(1, 3); $i++) { // Maksimal 3 produk per transaksi
                $product = $products->random();
                $jumlah = rand(1, 5); // Maksimal 5 produk per item
                $subtotal = $product->harga * $jumlah;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $product->harga,
                    'subtotal' => $subtotal,
                ]);

                $totalBayar += $subtotal;
            }

            // Update total_bayar dan kembalian
            $transaction->update([
                'total_bayar' => $totalBayar,
                'kembalian' => 100000 - $totalBayar,
            ]);
        }
    }
}
