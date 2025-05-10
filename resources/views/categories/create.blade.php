@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle text-primary"></i> เพิ่มหมวดหมู่
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
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

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ชื่อหมวดหมู่</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">ประเภท</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>รายรับ</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>รายจ่าย</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ไอคอน</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-emoji-smile"></i></span>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                            value="{{ old('icon') }}" placeholder="bi-tag">
                        <span class="input-group-text">
                            <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none">
                                <i class="bi bi-question-circle"></i>
                            </a>
                        </span>
                    </div>
                    <small class="text-muted">ใช้ Bootstrap Icons (เช่น bi-tag, bi-house, bi-car)</small>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">สี</label>
                    <div class="input-group">
                        <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                            value="{{ old('color') }}">
                        <span class="input-group-text">เลือกสี</span>
                    </div>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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