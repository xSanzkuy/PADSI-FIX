<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        /* Styling untuk membuat laporan lebih menarik */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .subheading {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laporan Transaksi</h2>
        <p class="subheading">
            <strong>Periode:</strong> 
            {{ $startDate ? $startDate : 'Semua' }} 
            - 
            {{ $endDate ? $endDate : 'Semua' }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pegawai</th>
                    <th>Total Bayar</th>
                    <th>Nominal Pembayaran</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->tanggal }}</td>
                        <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
                        <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->nominal_pembayaran, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Laporan dihasilkan pada:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
