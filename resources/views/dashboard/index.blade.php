@extends('layouts.app')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm rounded">
            <div class="card-header bg-primary text-white">
                <strong>ยอดคงเหลือแต่ละบัญชี</strong>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 350px;">
                <canvas id="accountBalanceChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm rounded">
            <div class="card-header bg-success text-white">
                <strong>รายรับ/รายจ่าย 6 เดือนล่าสุด</strong>
            </div>
            <div class="card-body" style="height: 350px;">
                <canvas id="monthlyTransactionChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">รวมรายรับทั้งหมด</h5>
                <h3>{{ number_format($totalIncome, 2) }} บาท</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">รวมรายจ่ายทั้งหมด</h5>
                <h3>{{ number_format($totalExpense, 2) }} บาท</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">เงินคงเหลือสุทธิ</h5>
                <h3>{{ number_format($totalIncome - $totalExpense, 2) }} บาท</h3>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    const accountBalanceCtx = document.getElementById('accountBalanceChart').getContext('2d');
    new Chart(accountBalanceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($accountLabels) !!},
            datasets: [{
                data: {!! json_encode($accountBalances) !!},
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const monthlyTransactionCtx = document.getElementById('monthlyTransactionChart').getContext('2d');
    new Chart(monthlyTransactionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [
                {
                    label: 'รายรับ',
                    data: {!! json_encode($monthlyData->pluck('total_income')) !!},
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'รายจ่าย',
                    data: {!! json_encode($monthlyData->pluck('total_expense')) !!},
                    backgroundColor: '#F44336'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
