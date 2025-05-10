@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0">
            <i class="bi bi-wallet2 text-primary"></i> เพิ่มงบประมาณรายเดือน
        </h4>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('monthly-budgets.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" 
                            name="category_id" 
                            required>
                        <option value="">เลือกหมวดหมู่</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                <i class="bi {{ $category->icon }}" 
                                   style="color: {{ $category->color }}"></i>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">จำนวนเงิน <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount') }}" 
                               step="0.01" 
                               required>
                        <span class="input-group-text">บาท</span>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="month" class="form-label">เดือน <span class="text-danger">*</span></label>
                    <select class="form-select @error('month') is-invalid @enderror" 
                            id="month" 
                            name="month" 
                            required>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" 
                                    {{ old('month', now()->month) == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('month')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">ปี <span class="text-danger">*</span></label>
                    <select class="form-select @error('year') is-invalid @enderror" 
                            id="year" 
                            name="year" 
                            required>
                        @foreach(range(date('Y'), date('Y')-5) as $year)
                            <option value="{{ $year }}" 
                                    {{ old('year', now()->year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input @error('notify_when_exceeded') is-invalid @enderror" 
                               id="notify_when_exceeded" 
                               name="notify_when_exceeded" 
                               value="1" 
                               {{ old('notify_when_exceeded') ? 'checked' : '' }}>
                        <label class="form-check-label" for="notify_when_exceeded">
                            แจ้งเตือนเมื่องบประมาณเกินกำหนด
                        </label>
                    </div>
                    @error('notify_when_exceeded')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('monthly-budgets.index') }}" class="btn btn-light">
                    <i class="bi bi-x-circle"></i> ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> บันทึก
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 