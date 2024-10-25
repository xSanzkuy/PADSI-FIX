@extends('layouts.layout')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tambah Transaksi</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form id="transaction-form" action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="pegawai" class="form-label">Pegawai</label>
            <select class="form-select" name="pegawai_id" id="pegawai" required>
                <option value="" disabled selected>Pilih Pegawai</option>
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="member" class="form-label">Member (Opsional)</label>
            <select class="form-select" name="telp_pelanggan" id="member">
                <option value="" selected>Tidak Ada</option>
                @foreach($members as $member)
                    <option value="{{ $member->no_hp }}">{{ $member->nama }} ({{ $member->tingkat }})</option>
                @endforeach
            </select>
        </div>

        <div id="product-list" class="mb-4">
            <h4>Daftar Produk</h4>
            <button type="button" class="btn btn-primary mb-2" id="add-product">Tambah Produk</button>
            <div class="product-item row mb-2">
                <div class="col-md-4">
                    <select class="form-select product-dropdown" name="items[0][product_id]" required>
                        <option value="" disabled selected>Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->harga }}" data-stock="{{ $product->stok }}" data-image="{{ asset('storage/' . $product->gambar) }}">
                                {{ $product->nama_produk }} (Rp {{ number_format($product->harga, 0, ',', '.') }}, Stok: {{ $product->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[0][jumlah]" class="form-control quantity" min="1" placeholder="Jumlah" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control subtotal" readonly placeholder="Subtotal">
                </div>
                <div class="col-md-2">
                    <img src="" alt="Gambar Produk" class="img-thumbnail product-image" style="width: 100px; height: 100px; display: none;">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total Bayar</label>
            <input type="text" class="form-control" id="total" name="total_bayar" readonly>
        </div>

        <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Pembayaran</label>
            <input type="number" class="form-control" name="nominal" id="nominal" min="0" required>
        </div>

        <div class="mb-3">
            <label for="kembalian" class="form-label">Kembalian</label>
            <input type="text" class="form-control" id="kembalian" readonly>
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
    </form>
</div>

<script>
    let productIndex = 1;

    $('#add-product').click(function() {
        let newProductItem = $('.product-item').first().clone();
        newProductItem.find('select').attr('name', `items[${productIndex}][product_id]`).val('');
        newProductItem.find('.quantity').attr('name', `items[${productIndex}][jumlah]`).val('');
        newProductItem.find('.subtotal').val('');
        newProductItem.find('.product-image').attr('src', '').hide();
        $('#product-list').append(newProductItem);
        productIndex++;
    });

    $(document).on('change', '.product-dropdown', function() {
        let productItem = $(this).closest('.product-item');
        let imageUrl = $(this).find('option:selected').data('image');
        let price = parseFloat($(this).find('option:selected').data('price'));
        let quantity = parseInt(productItem.find('.quantity').val());

        // Menampilkan gambar produk
        let productImage = productItem.find('.product-image');
        if (imageUrl) {
            productImage.attr('src', imageUrl).show();
        } else {
            productImage.hide();
        }

        // Menghitung subtotal
        let subtotal = price * quantity;
        productItem.find('.subtotal').val(isNaN(subtotal) ? '' : `Rp ${subtotal.toLocaleString('id-ID')}`);
        calculateTotal();
    });

    $(document).on('input', '.quantity', function() {
        let productItem = $(this).closest('.product-item');
        let price = parseFloat(productItem.find('option:selected').data('price'));
        let quantity = parseInt($(this).val());
        let stock = parseInt(productItem.find('option:selected').data('stock'));

        // Mengecek stok
        if (quantity > stock) {
            alert('Stok tidak cukup untuk jumlah yang diinput.');
            $(this).val(stock);
            quantity = stock;
        }

        // Menghitung subtotal
        let subtotal = price * quantity;
        productItem.find('.subtotal').val(isNaN(subtotal) ? '' : `Rp ${subtotal.toLocaleString('id-ID')}`);
        calculateTotal();
    });

    $('#transaction-form').on('submit', function(e) {
        let total = $('#total').data('total') || 0;
        let nominal = parseFloat($('#nominal').val()) || 0;

        // Mengecek apakah nominal cukup
        if (nominal < total) {
            e.preventDefault();
            alert('Uang tidak cukup untuk melakukan pembayaran.');
        }
    });

    $('#nominal').on('input', function() {
        calculateChange();
    });

    function calculateTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            let subtotal = parseFloat($(this).val().replace(/[^\d]/g, ''));
            if (!isNaN(subtotal)) {
                total += subtotal;
            }
        });
        $('#total').val('Rp ' + total.toLocaleString('id-ID'));
        $('#total').data('total', total);
        calculateChange();
    }

    function calculateChange() {
        let total = $('#total').data('total') || 0;
        let nominal = parseFloat($('#nominal').val()) || 0;
        let kembalian = nominal - total;
        $('#kembalian').val(kembalian < 0 ? 'Rp 0' : 'Rp ' + kembalian.toLocaleString('id-ID'));
    }
</script>
@endsection
