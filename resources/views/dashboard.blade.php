@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Dashboard</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Income</div>
                <div class="card-body">
                    <h5 class="card-title" id="totalIncome">Loading...</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Outcome</div>
                <div class="card-body">
                    <h5 class="card-title" id="totalOutcome">Loading...</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Sales</div>
                <div class="card-body">
                    <h5 class="card-title" id="totalSales">Loading...</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <canvas id="transactionsChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fetch Total Income
        fetch('/dashboard/income')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalIncome').innerText = 'Rp ' + data;
            });

        // Fetch Total Outcome
        fetch('/dashboard/outcome')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalOutcome').innerText = 'Rp ' + data;
            });

        // Fetch Total Sales
        fetch('/dashboard/sales-count')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalSales').innerText = data + ' Sales';
            });

        // Fetch Transactions By Year and Render Chart
        fetch('/dashboard/transactions-by-year?tahun=2024')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('transactionsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Sales', 'Purchases'],
                        datasets: [{
                            label: 'Transactions in 2024',
                            data: [data.sales, data.purchases],
                            backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    });
</script>
@endsection
