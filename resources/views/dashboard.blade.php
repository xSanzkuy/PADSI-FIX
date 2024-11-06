@extends('layouts.layout')

@section('content')
<div class="container-fluid" style="overflow: hidden;">
    <div class="row text-center mb-4">
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <h2>{{ $totalTransactions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <h2>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Produk Terbaik</h5>
                    <h2>{{ $topProductName }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center">Tren Transaksi</h5>
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center">Distribusi Pendapatan Produk</h5>
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Terbaru</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions->take(3) as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transaction->tanggal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">3 Anggota Teratas</h5>
                    <ul class="list-group">
                        @foreach($topMembers as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $member->nama }}
                            <span class="badge bg-primary rounded-pill">
                                {{ $member->transactions_count }} Transaksi
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Statistik Terbaru</h5>
                    <div class="card-text">
                        <p>Pendapatan total hingga saat ini: <strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></p>
                        <p>Transaksi terakhir dilakukan pada: <strong>{{ $recentTransactions->first()->tanggal ?? 'Tidak ada data' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const transactionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($transactionDates) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($transactionTotals) !!},
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Rp${tooltipItem.raw.toLocaleString()}`;
                        }
                    }
                }
            }
        }
    });

    const productCtx = document.getElementById('productChart').getContext('2d');
    const productChart = new Chart(productCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($productNames) !!},
            datasets: [{
                label: 'Pendapatan Produk',
                data: {!! json_encode($productRevenue) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: Rp${tooltipItem.raw.toLocaleString()}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
