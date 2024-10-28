import Chart from 'chart.js/auto';

// Grafik Penjualan (Line Chart)
const salesChart = new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Penjualan (Rp)',
            data: [200000, 400000, 350000, 500000, 650000, 800000, 900000],
            fill: false,
            borderColor: 'blue',
            tension: 0.1
        }]
    },
    options: {
        responsive: true
    }
});

// Grafik Cash Flow (Bar Chart)
const cashFlowChart = new Chart(document.getElementById('cashFlowChart'), {
    type: 'bar',
    data: {
        labels: ['Pemasukan', 'Pengeluaran'],
        datasets: [{
            label: 'Keuangan (Rp)',
            data: [10000000, 7000000],
            backgroundColor: ['green', 'red']
        }]
    },
    options: {
        responsive: true
    }
});
