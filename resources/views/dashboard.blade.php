@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions</h5>
                    <h2>{{ $totalTransactions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">Top Product</h5>
                    <h2>{{ $topProductName }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center">Transaction Trends</h5>
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">Recent Transactions</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactionDates as $index => $date)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $date }}</td>
                                <td>Rp{{ number_format($transactionTotals[$index], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Top 3 Members</h5>
                    <ul class="list-group">
                        @foreach($topMembers as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $member->nama }}
                            <span class="badge bg-primary rounded-pill">
                                {{ $member->transactions_count }} Transactions
                            </span>
                        </li>
                        @endforeach
                    </ul>
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
                label: 'Revenue',
                data: {!! json_encode($transactionTotals) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
                }
            }
        }
    });
</script>
@endpush
