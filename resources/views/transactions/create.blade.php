@extends('layouts.layout')  

@section('title', 'Tambah Transaksi')  

@section('content')  
<div class="container mt-5">  
    <div class="row text-center mb-4">  
        <h1 class="display-4 fw-bold text-primary">Tambah Transaksi</h1>  
        <p class="lead">Isi detail transaksi di bawah ini.</p>  
    </div>  
    <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="shadow-lg p-5 rounded bg-white" onsubmit="return validateForm()">  
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
                <label for="member_id" class="form-label">Member (Opsional)</label>  
                <select class="form-control" id="member_id" name="member_id">  
                    <option value="" data-diskon="0">Tidak Ada</option>  
                    @foreach($members as $m)  
                        <option value="{{ $m->id }}" data-diskon="{{ $m->tingkat }}">  
                            {{ $m->nama }} ({{ ucfirst($m->tingkat) }})  
                        </option>  
                    @endforeach  
                </select>  
            </div>  
        </div>  

        <div id="product-container" class="mb-4">  
            <!-- Loop untuk menampilkan item produk dari keranjang -->  
            @foreach($cartItems as $index => $item)  
                <div class="card mb-3 product-item shadow-sm">  
                    <div class="card-body">  
                        <div class="row align-items-center">  
                            <div class="col-md-4">  
                                <label for="product" class="form-label">Pilih Produk</label>  
                                <select class="form-control product" name="items[{{ $index }}][product_id]" required>  
                                    <option value="{{ $item->product->id }}"   
                                            data-harga="{{ $item->product->harga }}"   
                                            data-stok="{{ $item->product->stok }}"   
                                            data-gambar="{{ asset('storage/' . $item->product->gambar) }}" selected>  
                                        {{ $item->product->nama_produk }} (Rp {{ number_format($item->product->harga, 0, ',', '.') }}, Stok: {{ $item->product->stok }})  
                                    </option>  
                                </select>  
                            </div>  
                            <div class="col-md-3">  
                                <label for="jumlah" class="form-label">Jumlah</label>  
                                <input type="number" class="form-control jumlah" name="items[{{ $index }}][jumlah]" min="1" value="{{ $item->quantity }}" required>  
                            </div>  
                            <div class="col-md-3">  
                                <label for="subtotal" class="form-label">Subtotal</label>  
                                <input type="text" class="form-control subtotal" readonly value="Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}">  
                            </div>  
                            <div class="col-md-2 text-end">  
                                <button type="button" class="btn btn-danger btn-sm remove-product mt-4"><i class="fas fa-trash-alt"></i> Hapus</button>  
                            </div>  
                        </div>  
                        <img src="{{ asset('storage/' . $item->product->gambar) }}" alt="Gambar Produk" class="img-thumbnail mt-3" style="width: 100px; height: 100px;">  
                    </div>  
                </div>  
            @endforeach  
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
<script>  
    document.addEventListener('DOMContentLoaded', function() {  
        const productContainer = document.getElementById('product-container');  
        const tambahProdukButton = document.getElementById('tambahProduk');  
        const totalBayarInput = document.getElementById('total_bayar');  
        const nominalInput = document.getElementById('nominal');  
        const kembalianInput = document.getElementById('kembalian');  
        const memberSelect = document.getElementById('member_id');  

        // Fungsi untuk memperbarui total bayar  
        function updateTotal() {  
            let totalBayar = 0;  
            let isValid = true;  

            document.querySelectorAll('.product-item').forEach(function(item) {  
                const jumlahInput = item.querySelector('.jumlah');  
                const jumlah = parseInt(jumlahInput.value) || 0;  
                const harga = parseFloat(item.querySelector('.product option:checked').dataset.harga) || 0;  
                const stok = parseInt(item.querySelector('.product option:checked').dataset.stok) || 0;  
                const subtotal = jumlah * harga;  
                
                item.querySelector('.subtotal').value = 'Rp ' + new Intl.NumberFormat().format(subtotal);  
                totalBayar += subtotal;  

                // Validasi stok  
                if (jumlah > stok) {  
                    Swal.fire({  
                        icon: 'error',  
                        title: 'Jumlah tidak valid',  
                        text: "Jumlah tidak boleh melebihi stok yang tersedia: " + stok,  
                    });  
                    jumlahInput.value = stok; // Reset jumlah ke stok maksimum  
                    isValid = false;  
                }  
            });  

            if (isValid) {  
                const selectedMember = memberSelect.options[memberSelect.selectedIndex];  
                const tingkatMember = selectedMember.dataset.diskon;  
                let diskon = 0;  

                if (tingkatMember === 'bronze') {  
                    diskon = 0.05;  
                } else if (tingkatMember === 'silver') {  
                    diskon = 0.10;  
                } else if (tingkatMember === 'gold') {  
                    diskon = 0.15;  
                }  

                totalBayar -= totalBayar * diskon;  
                totalBayarInput.value = 'Rp ' + new Intl.NumberFormat().format(totalBayar);  
                updateKembalian();  
            } else {  
                totalBayarInput.value = 'Rp 0';  
            }  
        }  

        // Fungsi untuk memperbarui nilai kembalian  
        function updateKembalian() {  
            const nominal = parseFloat(nominalInput.value) || 0;  
            const totalBayar = parseFloat(totalBayarInput.value.replace(/[^\d]/g, '')) || 0;  
            const kembalian = nominal - totalBayar;  
            kembalianInput.value = 'Rp ' + new Intl.NumberFormat().format(kembalian > 0 ? kembalian : 0);  
        }  

        // Event listener untuk memperbarui total bayar dan kembalian  
        productContainer.addEventListener('input', updateTotal);  
        memberSelect.addEventListener('change', updateTotal);  
        nominalInput.addEventListener('input', updateKembalian);  

        // Fungsi untuk menambah produk baru  
        tambahProdukButton.addEventListener('click', function() {  
            const newIndex = document.querySelectorAll('.product-item').length;  
            const productItemTemplate = `  
                <div class="card mb-3 product-item shadow-sm">  
                    <div class="card-body">  
                        <div class="row align-items-center">  
                            <div class="col-md-4">  
                                <select class="form-control product" name="items[${newIndex}][product_id]" required>  
                                    <option value="" disabled selected>Pilih Produk</option>  
                                    @foreach($products as $product)  
                                        <option value="{{ $product->id }}" data-harga="{{ $product->harga }}" data-stok="{{ $product->stok }}" data-gambar="{{ asset('storage/' . $product->gambar) }}">  
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
                        <img src="" alt="Gambar Produk" class="img-thumbnail mt-3" style="display: none; width: 100px; height: 100px;">  
                    </div>  
                </div>  
            `;  
            productContainer.insertAdjacentHTML('beforeend', productItemTemplate);  
            updateProductImage();  
        });  

        // Fungsi untuk memperbarui gambar produk  
        function updateProductImage() {  
            document.querySelectorAll('.product-item').forEach(function(item) {  
                const selectedOption = item.querySelector('.product').options[item.querySelector('.product').selectedIndex];  
                const imageUrl = selectedOption ? selectedOption.getAttribute('data-gambar') : '';  
                const productImg = item.querySelector('img');  
                
                if (imageUrl) {  
                    productImg.src = imageUrl;  
                    productImg.style.display = 'block';  
                } else {  
                    productImg.style.display = 'none';  
                }  
            });  
        }  

        // Update gambar saat halaman pertama kali dimuat untuk item keranjang  
        updateProductImage();  

        // Event listener untuk memperbarui gambar saat produk dipilih  
        productContainer.addEventListener('change', function(e) {  
            if (e.target.classList.contains('product')) {  
                updateProductImage();  
                updateTotal(); // Tambahkan ini untuk memastikan total dihitung saat produk dipilih  
            }  
        });  

        // Event listener untuk menghapus produk  
        productContainer.addEventListener('click', function(e) {  
            if (e.target.classList.contains('remove-product')) {  
                Swal.fire({  
                    title: 'Yakin ingin menghapus?',  
                    text: "Produk ini akan dihapus dari transaksi!",  
                    icon: 'warning',  
                    showCancelButton: true,  
                    confirmButtonColor: '#d62828',  
                    cancelButtonColor: '#6c757d',  
                    confirmButtonText: 'Ya, Hapus!',  
                    cancelButtonText: 'Batal'  
                }).then((result) => {  
                    if (result.isConfirmed) {  
                        e.target.closest('.product-item').remove();  
                        updateTotal();  
                    }  
                });  
            }  
        });  

        // Validasi form sebelum submit  
        window.validateForm = function() {  
            const nominal = parseFloat(nominalInput.value) || 0;  
            const totalBayar = parseFloat(totalBayarInput.value.replace(/[^\d]/g, '')) || 0;  
            
            if (nominal < totalBayar) {  
                Swal.fire({  
                    icon: 'error',  
                    title: 'Pembayaran Tidak Cukup',  
                    text: 'Nominal pembayaran tidak cukup!',  
                });  
                return false;  
            }  
            return true;  
        }  

        // Jalankan updateTotal() pada saat halaman pertama kali dimuat  
        updateTotal();  
    });  
</script>  

@endsection