@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>รายการรายรับรายจ่าย</h4>
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
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>วันที่</th>
                        <th>บัญชี</th>
                        <th>ประเภท</th>
                        <th>หมวดหมู่</th>
                        <th>จำนวน</th>
                        <th>หมายเหตุ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $t)
                        <tr>
                            <td>{{ $t->date }}</td>
                            <td>{{ $t->bankAccount->name }}</td>
                            <td>{{ $t->type }}</td>
                            <td>{{ $t->category }}</td>
                            <td>{{ number_format($t->amount, 2) }}</td>
                            <td>{{ $t->note }}</td>
                            <td>
                                <a href="{{ route('transactions.edit', $t) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> แก้ไข
                                </a>
                                <form action="{{ route('transactions.destroy', $t) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('ลบข้อมูลนี้?')">
                                        <i class="bi bi-trash"></i> ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">-- ประเภท --</option>
                        <option value="income">รายรับ</option>
                        <option value="expense">รายจ่าย</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="month" class="form-select">
                        <option value="">-- เดือน --</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="year" class="form-control" placeholder="ปี (เช่น 2025)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </form>

        </div>
    </div>
@endsection
