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

    <div class="row mt-4">
        <div class="col-md-12">
            <h3 class="text-center">Member Teraktif</h3>
            <div class="card">
                <div class="card-header">3 Member dengan Jumlah Transaksi Terbanyak</div>
                <div class="card-body">
                    <ul id="topMembersList" class="list-group">
                        <li class="list-group-item">Loading...</li>
                    </ul>
                </div>
            </div>
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

        // Fetch Top 3 Members with Most Transactions
        fetch('/dashboard/top-members')
            .then(response => response.json())
            .then(data => {
                const topMembersList = document.getElementById('topMembersList');
                topMembersList.innerHTML = ''; // Clear loading text

                data.forEach(member => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = `${member.name} - ${member.transaction_count} Transaksi`;
                    topMembersList.appendChild(li);
                });
            });
    });
</script>
@endsection
