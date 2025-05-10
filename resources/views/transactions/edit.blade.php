@extends('layouts.app')

@section('content')
<h1>แก้ไขรายการ</h1>
<form action="{{ route('transactions.update', $transaction) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>บัญชี:</label>
        <select name="bank_account_id" class="form-control" required>
            @foreach ($bankAccounts as $account)
                <option value="{{ $account->id }}" {{ $transaction->bank_account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>วันที่:</label>
        <input type="date" name="date" value="{{ $transaction->date }}" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>ประเภท:</label>
        <select name="type" class="form-control">
            <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>รายรับ</option>
            <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>รายจ่าย</option>
        </select>
    </div>
    <div class="mb-2">
        <label>หมวดหมู่:</label>
        <input type="text" name="category" value="{{ $transaction->category }}" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>จำนวนเงิน:</label>
        <input type="number" step="0.01" name="amount" value="{{ $transaction->amount }}" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>หมายเหตุ:</label>
        <textarea name="note" class="form-control">{{ $transaction->note }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">อัปเดต</button>
</form>
@endsection
