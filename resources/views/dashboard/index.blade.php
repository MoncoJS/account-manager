@extends('layouts.app')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">รวมรายรับทั้งหมด</h6>
                        <h3 class="mt-2 mb-0">{{ number_format($totalIncome, 2) }} บาท</h3>
                    </div>
                    <i class="bi bi-arrow-up-circle" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">รวมรายจ่ายทั้งหมด</h6>
                        <h3 class="mt-2 mb-0">{{ number_format($totalExpense, 2) }} บาท</h3>
                    </div>
                    <i class="bi bi-arrow-down-circle" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">เงินคงเหลือสุทธิ</h6>
                        <h3 class="mt-2 mb-0">{{ number_format($totalIncome - $totalExpense, 2) }} บาท</h3>
                    </div>
                    <i class="bi bi-wallet2" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart"></i> ยอดคงเหลือแต่ละบัญชี
                </h5>
            </div>
            <div class="card-body">
                <canvas id="accountBalanceChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> รายรับ/รายจ่าย 6 เดือนล่าสุด
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyTransactionChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Account Balance Chart
    const accountBalanceCtx = document.getElementById('accountBalanceChart').getContext('2d');
    new Chart(accountBalanceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($accountLabels) !!},
            datasets: [{
                data: {!! json_encode($accountBalances) !!},
                backgroundColor: [
                    '#36A2EB',
                    '#FF6384',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            return label + ': ' + value.toLocaleString() + ' บาท';
                        }
                    }
                }
            }
        }
    });

    // Monthly Transaction Chart
    const monthlyData = {!! json_encode($monthlyData) !!};
    const monthlyTransactionCtx = document.getElementById('monthlyTransactionChart').getContext('2d');
    new Chart(monthlyTransactionCtx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'รายรับ',
                    data: monthlyData.map(item => item.total_income),
                    backgroundColor: '#28a745'
                },
                {
                    label: 'รายจ่าย',
                    data: monthlyData.map(item => item.total_expense),
                    backgroundColor: '#dc3545'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' บาท';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.raw || 0;
                            return label + ': ' + value.toLocaleString() + ' บาท';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection

