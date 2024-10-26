@extends('layouts.layout')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container mt-5">
    <div class="row text-center mb-4">
        <h1 class="display-4 fw-bold text-primary">Tambah Transaksi</h1>
        <p class="lead">Isi detail transaksi di bawah ini.</p>
    </div>
    <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="shadow-lg p-5 rounded bg-white">
        @csrf
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label for="pegawai_id" class="form-label">Pegawai</label>
                <select class="form-control" id="pegawai_id" name="pegawai_id" required>
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="member" class="form-label">Member (Opsional)</label>
                <select class="form-control" id="member" name="telp_pelanggan">
                    <option value="" data-diskon="0">Tidak Ada</option>
                    @foreach($members as $m)
                        <option value="{{ $m->no_hp }}" data-diskon="{{ $m->tingkat }}">
                            {{ $m->nama }} ({{ ucfirst($m->tingkat) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="product-container" class="mb-4">
            <div class="card mb-3 product-item shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label for="product" class="form-label">Pilih Produk</label>
                            <select class="form-control product" name="items[0][product_id]" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga }}" data-stok="{{ $product->stok }}">
                                        {{ $product->nama_produk }} (Rp {{ number_format($product->harga, 0, ',', '.') }}, Stok: {{ $product->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control jumlah" name="items[0][jumlah]" min="1" value="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal" readonly value="Rp 0">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-product mt-4"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-primary mb-3" id="tambahProduk"><i class="fas fa-plus"></i> Tambah Produk</button>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="total_bayar" class="form-label">Total Bayar</label>
                <input type="text" class="form-control form-control-lg" id="total_bayar" readonly value="Rp 0">
            </div>
            <div class="col-md-6">
                <label for="nominal" class="form-label">Nominal Pembayaran</label>
                <input type="number" class="form-control form-control-lg" id="nominal" name="nominal" required>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="kembalian" class="form-label">Kembalian</label>
                <input type="text" class="form-control form-control-lg" id="kembalian" readonly value="Rp 0">
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Simpan Transaksi</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productContainer = document.getElementById('product-container');
        const tambahProdukButton = document.getElementById('tambahProduk');
        const totalBayarInput = document.getElementById('total_bayar');
        const nominalInput = document.getElementById('nominal');
        const kembalianInput = document.getElementById('kembalian');
        const memberSelect = document.getElementById('member');

        function updateTotal() {
            let totalBayar = 0;
            document.querySelectorAll('.product-item').forEach(function(item) {
                const jumlah = item.querySelector('.jumlah').value;
                const harga = item.querySelector('.product option:checked').dataset.harga;
                const subtotal = jumlah * harga;
                item.querySelector('.subtotal').value = 'Rp ' + new Intl.NumberFormat().format(subtotal);
                totalBayar += subtotal;
            });

            // Terapkan diskon jika member dipilih
            const selectedMember = memberSelect.options[memberSelect.selectedIndex];
            const tingkatMember = selectedMember.dataset.diskon;
            let diskon = 0;

            if (tingkatMember === 'bronze') {
                diskon = 0.05; // 5%
            } else if (tingkatMember === 'silver') {
                diskon = 0.10; // 10%
            } else if (tingkatMember === 'gold') {
                diskon = 0.15; // 15%
            }

            if (diskon > 0) {
                totalBayar -= totalBayar * diskon;
            }

            totalBayarInput.value = 'Rp ' + new Intl.NumberFormat().format(totalBayar);

            // Update kembalian
            updateKembalian();
        }

        function updateKembalian() {
            const nominal = parseFloat(nominalInput.value) || 0;
            const totalBayar = parseFloat(totalBayarInput.value.replace(/[^\d]/g, '')) || 0;
            const kembalian = nominal - totalBayar;
            kembalianInput.value = 'Rp ' + new Intl.NumberFormat().format(kembalian > 0 ? kembalian : 0);
        }

        productContainer.addEventListener('input', updateTotal);
        memberSelect.addEventListener('change', updateTotal);
        nominalInput.addEventListener('input', updateKembalian);

        tambahProdukButton.addEventListener('click', function() {
            const newIndex = document.querySelectorAll('.product-item').length;
            const productItemTemplate = `
                <div class="card mb-3 product-item shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <select class="form-control product" name="items[${newIndex}][product_id]" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-harga="{{ $product->harga }}" data-stok="{{ $product->stok }}">
                                            {{ $product->nama_produk }} (Rp {{ number_format($product->harga, 0, ',', '.') }}, Stok: {{ $product->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control jumlah" name="items[${newIndex}][jumlah]" min="1" value="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control subtotal" readonly value="Rp 0">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash-alt"></i> Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productContainer.insertAdjacentHTML('beforeend', productItemTemplate);
        });

        productContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-item').remove();
                updateTotal();
            }
        });
    });
</script>

@endsection
