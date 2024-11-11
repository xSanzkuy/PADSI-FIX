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
        }
        h2 {
            text-align: center;
            font-size: 24px;
            color: #0033a0; /* Warna biru sesuai dengan tema */
            margin-bottom: 5px;
        }
        .subheading {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        /* Tabel styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #0033a0; /* Warna biru sesuai tema */
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 12px;
        }
        td {
            text-align: center;
            background-color: #f4f6f8;
        }
        /* Styling untuk baris bergaris */
        tr:nth-child(even) td {
            background-color: #e9ecef;
        }

        /* Footer styling */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        /* Tambahan shadow effect */
        .shadow {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
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
                    <th>Total Bayar</th>
                    <th>Nominal Pembayaran</th>
                    <th>Kembalian</th>
                    <th>Tingkat Member</th> <!-- Kolom untuk tingkat member -->
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
                        <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                        <td>{{ $transaction->member ? ucfirst($transaction->member->tingkat) : 'N/A' }}</td> <!-- Tampilkan tingkat member -->
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
        