@extends('layouts.app')

@section('content')
<h1 class="mb-4">Dashboard</h1>

{{-- SUMMARY CARDS --}}
<div class="row mb-4">
    @php
        $cards = [
            ['title' => 'รวมรายรับทั้งหมด', 'value' => $totalIncome, 'bg' => 'success', 'icon' => 'bi-arrow-up-circle'],
            ['title' => 'รวมรายจ่ายทั้งหมด', 'value' => $totalExpense, 'bg' => 'danger', 'icon' => 'bi-arrow-down-circle'],
            ['title' => 'เงินคงเหลือสุทธิ', 'value' => $totalIncome - $totalExpense, 'bg' => 'primary', 'icon' => 'bi-wallet2'],
        ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-md-4">
        <div class="card text-white bg-{{ $card['bg'] }} h-100 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">{{ $card['title'] }}</h6>
                    <h3 class="mb-0">{{ number_format($card['value'], 2) }} บาท</h3>
                </div>
                <i class="bi {{ $card['icon'] }}" style="font-size: 2.2rem;"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- CHARTS --}}
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart-fill"></i> ยอดคงเหลือแต่ละบัญชี
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="accountBalanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart-line-fill"></i> รายรับ/รายจ่าย 6 เดือนล่าสุด
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="monthlyTransactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Chart 1: Account Balance
    new Chart(document.getElementById('accountBalanceChart'), {
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
            plugins: {
                legend: { position: 'right' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.label}: ${ctx.raw.toLocaleString()} บาท`
                    }
                }
            }
        }
    });

    // Chart 2: Monthly Transaction
    const monthlyData = {!! json_encode($monthlyData) !!};
    new Chart(document.getElementById('monthlyTransactionChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'รายรับ',
                    data: monthlyData.map(item => item.total_income),
                    backgroundColor: '#198754'
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
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString() + ' บาท'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.raw.toLocaleString()} บาท`
                    }
                }
            }
        }
    });
});
</script>
@endpush
