@extends('layouts.app')

@section('content')
<h1>เพิ่มรายการ</h1>
<form action="{{ route('transactions.store') }}" method="POST">
    @csrf
    <div class="mb-2">
        <label>บัญชี:</label>
        <select name="bank_account_id" class="form-control" required>
            @foreach ($bankAccounts as $account)
                <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>วันที่:</label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>ประเภท:</label>
        <select name="type" class="form-control">
            <option value="income">รายรับ</option>
            <option value="expense">รายจ่าย</option>
        </select>
    </div>
    <div class="mb-2">
        <label>หมวดหมู่:</label>
        <input type="text" name="category" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>จำนวนเงิน:</label>
        <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>หมายเหตุ:</label>
        <textarea name="note" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">บันทึก</button>
</form>
@endsection
