@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>บัญชีธนาคาร</h4>
        <a href="{{ route('bank-accounts.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> เพิ่มบัญชี
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ชื่อธนาคาร</th><th>เลขบัญชี</th><th>ยอดเริ่มต้น</th><th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($bankAccounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->account_number }}</td>
                    <td>{{ number_format($account->initial_balance,2) }}</td>
                    <td>
                        <a href="{{ route('bank-accounts.edit', $account) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> แก้ไข
                        </a>
                        <form action="{{ route('bank-accounts.destroy', $account) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('ลบข้อมูลนี้?')">
                                <i class="bi bi-trash"></i> ลบ
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
