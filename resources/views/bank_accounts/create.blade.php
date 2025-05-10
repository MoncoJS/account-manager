@extends('layouts.app')

@section('content')
    <h1>เพิ่มบัญชีธนาคาร</h1>
    <form action="{{ route('bank-accounts.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>เกิดข้อผิดพลาด:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-2">
            <label>ชื่อธนาคาร:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>เลขบัญชี:</label>
            <input type="text" name="account_number" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>ยอดเริ่มต้น:</label>
            <input type="number" step="0.01" name="initial_balance" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
@endsection
