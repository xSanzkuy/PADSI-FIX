@extends('layouts.layout')  

@section('title', 'Laporan Transaksi')  

@section('content')  
<div class="container mt-5">  
    <!-- Header Halaman -->  
    <div class="d-flex justify-content-between align-items-center mb-4">  
        <h3 class="fw-bold page-title">📊 Laporan Transaksi</h3>  
        <a href="{{ route('reports.exportPDF', ['start_date' => $startDate, 'end_date' => $endDate, 'member_level' => request('member_level')]) }}" class="btn btn-danger shadow rounded-pill px-4 py-2">  
            <i class="fas fa-file-pdf"></i> Export to PDF  
        </a>  
    </div>  

    <!-- Tabel dan Filter Laporan Transaksi -->  
    <div class="card shadow-lg border-0 rounded-lg">  
        <div class="card-header text-white text-center table-header">  
            <h4 class="mb-0">Filter dan Laporan Transaksi</h4>  
        </div>  
        <div class="card-body p-4">  
            <!-- Filter Form -->  
            <form action="{{ route('reports.index') }}" method="GET" class="row gx-3 gy-3 align-items-end">  
                <div class="col-md-3">  
                    <label for="start_date" class="form-label">Tanggal Mulai</label>  
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="form-control">  
                </div>  
                <div class="col-md-3">  
                    <label for="end_date" class="form-label">Tanggal Akhir</label>  
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="form-control">  
                </div>  
                <div class="col-md-3">  
                    <label for="member_level" class="form-label">Tingkat Member</label>  
                    <select name="member_level" id="member_level" class="form-select">  
    <option value="">Semua Tingkat</option>  
    <option value="N/A" {{ request('member_level') == 'N/A' ? 'selected' : '' }}>N/A</option> 
    <option value="bronze" {{ request('member_level') == 'bronze' ? 'selected' : '' }}>Bronze</option>  
    <option value="silver" {{ request('member_level') == 'silver' ? 'selected' : '' }}>Silver</option>  
    <option value="gold" {{ request('member_level') == 'gold' ? 'selected' : '' }}>Gold</option>  
</select>

                    </select>  
                </div>  
                <div class="col-md-3 d-flex justify-content-end">  
                    <button type="submit" class="btn btn-primary shadow rounded-pill w-100">  
                        <i class="fas fa-filter"></i> Filter  
                    </button>  
                </div>  
            </form>  

            <!-- Tabel Transaksi -->  
            @if($transactions->isEmpty())  
                <div class="alert alert-warning text-center">  
                    Tidak ada transaksi yang ditemukan.  
                </div>  
            @else  
                <div class="table-responsive">  
                    <table class="table table-bordered table-hover align-middle">  
                        <thead class="bg-light">  
                            <tr class="text-center table-head-row">  
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
                                    <td class="text-center">{{ $transaction->id }}</td>  
                                    <td>{{ $transaction->tanggal }}</td>  
                                    <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td> 
                                    <td>{{ $transaction->member ? $transaction->member->nama : 'Tidak Ada Member' }}</td>
                                    <td class="text-center">{{ $transaction->member ? ucfirst($transaction->member->tingkat) : 'N/A' }}</td>   
                                    <td class="text-end">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>  
                                    <td class="text-end">Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>  
                                    <td class="text-end">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>  
                                </tr>  
                            @endforeach  
                        </tbody>  
                    </table>  
                </div>  

                <!-- Pagination -->  
                <div class="d-flex justify-content-center mt-4">  
                    {{ $transactions->appends(['start_date' => $startDate, 'end_date' => $endDate, 'member_level' => request('member_level')])->links() }}  
                </div>  
            @endif  
        </div>  
    </div>  
</div>  
@endsection