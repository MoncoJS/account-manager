@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle"></i> เพิ่มบัญชีธนาคาร
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('bank-accounts.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">ชื่อธนาคาร</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">เลขบัญชี</label>
                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                    value="{{ old('account_number') }}" required>
                @error('account_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">ยอดเริ่มต้น</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="initial_balance" 
                        class="form-control @error('initial_balance') is-invalid @enderror" 
                        value="{{ old('initial_balance') }}" required>
                    <span class="input-group-text">บาท</span>
                    @error('initial_balance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('bank-accounts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> บันทึก
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
