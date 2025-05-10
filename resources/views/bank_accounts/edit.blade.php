@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>แก้ไขบัญชีธนาคาร</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('bank-accounts.update', $bankAccount) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>ชื่อธนาคาร:</label>
                <input type="text" name="name" value="{{ old('name', $bankAccount->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>เลขบัญชี:</label>
                <input type="text" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>ยอดเริ่มต้น:</label>
                <input type="number" step="0.01" name="initial_balance" value="{{ old('initial_balance', $bankAccount->initial_balance) }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> อัปเดต
            </button>
        </form>
    </div>
</div>
@endsection
