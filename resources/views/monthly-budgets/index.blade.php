@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0">
            <i class="bi bi-wallet2 text-primary"></i> งบประมาณรายเดือน
        </h4>
        <a href="{{ route('monthly-budgets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> เพิ่มงบประมาณ
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <select class="form-select" id="monthFilter">
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="yearFilter">
                    @foreach(range(date('Y'), date('Y')-5) as $year)
                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($budgets->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-wallet2 text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted h5">ยังไม่มีงบประมาณรายเดือน</p>
                <p class="text-muted">เริ่มต้นโดยการเพิ่มงบประมาณสำหรับหมวดหมู่ต่างๆ</p>
                <a href="{{ route('monthly-budgets.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> เพิ่มงบประมาณแรก
                </a>
            </div>
        @else
            <div class="row">
                @foreach($budgets as $budget)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="bi {{ $budget->category->icon ?? 'bi-tag' }} me-2" 
                                               style="color: {{ $budget->category->color ?? '#6c757d' }}"></i>
                                            {{ $budget->category->name }}
                                        </h5>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('monthly-budgets.edit', $budget) }}" 
                                           class="btn btn-outline-warning btn-sm"
                                           data-bs-toggle="tooltip" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('monthly-budgets.destroy', $budget) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('คุณต้องการลบงบประมาณนี้ใช่หรือไม่?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="tooltip" 
                                                    title="ลบ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">งบประมาณ</span>
                                        <span class="fw-medium">{{ number_format($budget->amount, 2) }} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">ใช้ไปแล้ว</span>
                                        <span class="fw-medium">{{ number_format($budget->getSpentAmount(), 2) }} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">คงเหลือ</span>
                                        <span class="fw-medium {{ $budget->getRemainingAmount() < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($budget->getRemainingAmount(), 2) }} บาท
                                        </span>
                                    </div>
                                </div>

                                <div class="progress" style="height: 8px;">
                                    @php
                                        $percentage = $budget->getProgressPercentage();
                                        $bgClass = $percentage > 100 ? 'bg-danger' : ($percentage > 80 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress-bar {{ $bgClass }}" 
                                         role="progressbar" 
                                         style="width: {{ min(100, $percentage) }}%"
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>

                                @if($budget->getRemainingAmount() < 0)
                                    <div class="alert alert-danger mt-3 mb-0 py-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        งบประมาณเกินกำหนด
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        params.set('month', monthFilter.value);
        params.set('year', yearFilter.value);
        window.location.search = params.toString();
    }

    monthFilter.addEventListener('change', applyFilters);
    yearFilter.addEventListener('change', applyFilters);
});
</script>
@endpush
@endsection 