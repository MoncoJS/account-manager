@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="bi bi-bank"></i> บัญชีธนาคาร
        </h4>
        <a href="{{ route('bank-accounts.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> เพิ่มบัญชี
        </a>
    </div>
    <div class="card-body">
        @if($bankAccounts->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bank text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">ยังไม่มีบัญชีธนาคาร</p>
                <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> เพิ่มบัญชีแรก
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อธนาคาร</th>
                            <th>เลขบัญชี</th>
                            <th class="text-end">ยอดเริ่มต้น</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bankAccounts as $account)
                            <tr>
                                <td>
                                    <i class="bi bi-bank me-2"></i>
                                    {{ $account->name }}
                                </td>
                                <td>{{ $account->account_number }}</td>
                                <td class="text-end">{{ number_format($account->initial_balance, 2) }} บาท</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('bank-accounts.edit', $account) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> แก้ไข
                                        </a>
                                        <form action="{{ route('bank-accounts.destroy', $account) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('คุณต้องการลบบัญชีนี้ใช่หรือไม่?')">
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
@endsection
