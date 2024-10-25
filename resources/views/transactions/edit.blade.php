@extends('layouts.layout')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Transaksi</h1>

    <form id="transaction-form" action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ \Carbon\Carbon::parse($transaction->tanggal)->format('Y-m-d') }}" required readonly>
        </div>

        <div class="mb-3">
            <label for="pegawai" class="form-label">Pegawai</label>
            <select class="form-select" name="pegawai_id" id="pegawai" required>
                <option value="" disabled>Pilih Pegawai</option>
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}" {{ $transaction->pegawai_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <div id="product-list" class="mb-4">
            <h4>Daftar Produk</h4>
            <button type="button" class="btn btn-primary mb-2" id="add-product">Tambah Produk</button>
            @foreach($transaction->details as $index => $detail)
                <div class="product-item row mb-2">
                    <div class="col-md-4">
                        <select class="form-select product-dropdown" name="items[{{ $index }}][product_id]" required>
                            <option value="" disabled>Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->harga }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->nama_produk }} (Rp {{ number_format($product->harga, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[{{ $index }}][jumlah]" class="form-control quantity" min="1" value="{{ $detail->jumlah }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control subtotal" value="Rp {{ number_format($detail->subtotal, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-product">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total Bayar</label>
            <input type="text" class="form-control" id="total" name="total_bayar" readonly value="Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}">
        </div>

        <div class="mb-3">
            <label for="nominal" class="form-label">Nominal Pembayaran</label>
            <input type="number" class="form-control" name="nominal" id="nominal" value="{{ $transaction->nominal }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="kembalian" class="form-label">Kembalian</label>
            <input type="text" class="form-control" id="kembalian" readonly value="Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}">
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>

<script>
    let productIndex = {{ count($transaction->details) }};

    $('#add-product').click(function() {
        let newProductItem = $('.product-item').first().clone();
        newProductItem.find('select').attr('name', `items[${productIndex}][product_id]`).val('');
        newProductItem.find('.quantity').attr('name', `items[${productIndex}][jumlah]`).val('');
        newProductItem.find('.subtotal').val('');
        $('#product-list').append(newProductItem);
        productIndex++;
    });

    $(document).on('click', '.remove-product', function() {
        if ($('.product-item').length > 1) {
            $(this).closest('.product-item').remove();
            calculateTotal();
        }
    });

    $(document).on('change', '.quantity, .product-dropdown', function() {
        let productItem = $(this).closest('.product-item');
        let price = parseFloat(productItem.find('option:selected').data('price'));
        let quantity = parseInt(productItem.find('.quantity').val());
        let subtotal = price * quantity;
        productItem.find('.subtotal').val(isNaN(subtotal) ? '' : `Rp ${subtotal.toLocaleString('id-ID')}`);
        calculateTotal();
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
