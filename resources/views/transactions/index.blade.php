@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-cash-stack"></i> รายการรายรับรายจ่าย
            </h4>
            <div>
                <a href="{{ route('transactions.create') }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> เพิ่มรายการ
                </a>
                <a href="{{ url('tax/' . date('Y')) }}" class="btn btn-info">
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
                    <i class="bi bi-cash-stack text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">ยังไม่มีรายการรายรับรายจ่าย</p>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> เพิ่มรายการแรก
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>วันที่</th>
                                <th>บัญชี</th>
                                <th>ประเภท</th>
                                <th>หมวดหมู่</th>
                                <th class="text-end">จำนวน</th>
                                <th>หมายเหตุ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $t)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($t->date)) }}</td>
                                    <td>
                                        <i class="bi bi-bank me-2"></i>
                                        {{ $t->bankAccount->name }}
                                    </td>
                                    <td>
                                        @if($t->type == 'income')
                                            <span class="badge bg-success">รายรับ</span>
                                        @else
                                            <span class="badge bg-danger">รายจ่าย</span>
                                        @endif
                                    </td>
                                    <td>{{ $t->category }}</td>
                                    <td class="text-end">
                                        {{ number_format($t->amount, 2) }} บาท
                                    </td>
                                    <td>{{ $t->note }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('transactions.edit', $t) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> แก้ไข
                                            </a>
                                            <form action="{{ route('transactions.destroy', $t) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')">
                                                    <i class="bi bi-trash"></i> ลบ
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
@endsection
