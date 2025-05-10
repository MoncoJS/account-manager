@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0">
                <i class="bi bi-cash-stack text-primary"></i> รายการรายรับรายจ่าย
            </h4>
            <div>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> เพิ่มรายการ
                </a>
                <a href="{{ url('tax/' . date('Y')) }}" class="btn btn-outline-info">
                    <i class="bi bi-calculator"></i> ภาษีปีนี้
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <select class="form-select" id="typeFilter">
                        <option value="">ทุกประเภท</option>
                        <option value="income">รายรับ</option>
                        <option value="expense">รายจ่าย</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="monthFilter">
                        <option value="">ทุกเดือน</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="yearFilter">
                        <option value="">ทุกปี</option>
                        @foreach(range(date('Y'), date('Y')-5) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($transactions->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-cash-stack text-muted" style="font-size: 4rem;"></i>
                    <p class="mt-3 text-muted h5">ยังไม่มีรายการรายรับรายจ่าย</p>
                    <p class="text-muted">เริ่มต้นโดยการเพิ่มรายการรายรับรายจ่ายของคุณ</p>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> เพิ่มรายการแรก
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">วันที่</th>
                                <th class="py-3">บัญชี</th>
                                <th class="py-3">ประเภท</th>
                                <th class="py-3">หมวดหมู่</th>
                                <th class="py-3 text-end">จำนวน</th>
                                <th class="py-3">หมายเหตุ</th>
                                <th class="py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $t)
                                <tr class="transition">
                                    <td>
                                        <span class="text-muted">{{ date('d/m/Y', strtotime($t->date)) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bank me-2 text-primary"></i>
                                            <span class="fw-medium">{{ $t->bankAccount->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($t->type == 'income')
                                            <span class="badge bg-success-subtle text-success">รายรับ</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">รายจ่าย</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $t->category }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium {{ $t->type == 'income' ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($t->amount, 2) }} บาท
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $t->note }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('transactions.edit', $t) }}" 
                                                class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="tooltip" 
                                                title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $t) }}" 
                                                method="POST" 
                                                class="d-inline"
                                                onsubmit="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="tooltip" 
                                                    title="ลบ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        const typeFilter = document.getElementById('typeFilter');
        const monthFilter = document.getElementById('monthFilter');
        const yearFilter = document.getElementById('yearFilter');

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            if (typeFilter.value) params.set('type', typeFilter.value);
            if (monthFilter.value) params.set('month', monthFilter.value);
            if (yearFilter.value) params.set('year', yearFilter.value);
            window.location.search = params.toString();
        }

        typeFilter.addEventListener('change', applyFilters);
        monthFilter.addEventListener('change', applyFilters);
        yearFilter.addEventListener('change', applyFilters);

        // Set initial values from URL
        const urlParams = new URLSearchParams(window.location.search);
        typeFilter.value = urlParams.get('type') || '';
        monthFilter.value = urlParams.get('month') || '';
        yearFilter.value = urlParams.get('year') || '';
    });
    </script>
    @endpush

    <style>
    .transition {
        transition: all 0.2s ease-in-out;
    }
    .transition:hover {
        background-color: rgba(0,0,0,.02);
    }
    .bg-success-subtle {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1);
    }
    </style>
@endsection
