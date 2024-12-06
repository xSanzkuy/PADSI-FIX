<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
    /* Styling dasar */
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
    }
    .container {
        padding: 20px;
        max-width: 900px;
        margin: auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        font-size: 24px;
        color: #0033a0;
        margin-bottom: 5px;
    }
    .subheading {
        text-align: center;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    /* Tabel styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table th, table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #0033a0;
        color: white;
        font-weight: bold;
        text-align: center;
    }
    td {
        background-color: #f4f6f8;
    }
    tr:nth-child(even) td {
        background-color: #e9ecef;
    }

    /* Styling untuk total transaksi */
    tfoot tr td {
        font-weight: bold;
        background-color: #d6e4f0;
        border-top: 2px solid #0033a0;
    }

    /* Footer styling */
    .footer {
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        color: #666;
    }
</style>

</head>
<body>
    <div class="container shadow">
        <h2>Laporan Transaksi</h2>
        <p class="subheading">
            <strong>Periode:</strong> 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d-m-Y') : 'Semua' }} 
            - 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d-m-Y') : 'Semua' }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pegawai</th>
                    <th>Nama Member</th>
                    <th>Tingkat Member</th>
                    <th>Total Bayar</th>
                    <th>Nominal Pembayaran</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($transactions as $transaction)
        <tr>
            <td>{{ $transaction->id }}</td>
            <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
            <td>{{ $transaction->member ? $transaction->member->nama : 'Tidak Ada Member' }}</td>
            <td>{{ $transaction->member ? ucfirst($transaction->member->tingkat) : 'N/A' }}</td>
            <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>

        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <td colspan="3" style="text-align: right; font-weight: bold;">Total Transaksi:</td>
        <td colspan="4" style="text-align: center; font-weight: bold;">
            Rp {{ number_format($transactions->sum('total_bayar'), 0, ',', '.') }}
        </td>
    </tr>
</tfoot>

        <div class="footer">
            <p><strong>Laporan dihasilkan pada:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
        