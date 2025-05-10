@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle"></i> เพิ่มรายการ
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('transactions.store') }}" method="POST">
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
                    <label class="form-label">บัญชี</label>
                    <select name="bank_account_id" class="form-select @error('bank_account_id') is-invalid @enderror" required>
                        <option value="">เลือกบัญชี</option>
                        @foreach ($bankAccounts as $account)
                            <option value="{{ $account->id }}" {{ old('bank_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} ({{ $account->account_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('bank_account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">วันที่</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                        value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ประเภท</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>รายรับ</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>รายจ่าย</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">หมวดหมู่</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">เลือกหมวดหมู่</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                    data-type="{{ $category->type }}"
                                    data-icon="{{ $category->icon }}"
                                    data-color="{{ $category->color }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">จำนวนเงิน</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                            value="{{ old('amount') }}" required>
                        <span class="input-group-text">บาท</span>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">หมายเหตุ</label>
                    <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" 
                        value="{{ old('note') }}">
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');
    const categoryOptions = Array.from(categorySelect.querySelectorAll('option[data-type]'));
    const categories = categoryOptions.map(option => ({
        value: option.value,
        text: option.textContent,
        type: option.dataset.type,
        icon: option.dataset.icon,
        color: option.dataset.color
    }));

    function filterCategories() {
        const selectedType = typeSelect.value;
        categoryOptions.forEach(option => {
            if (option.value === '') return; // Skip the placeholder option
            if (option.dataset.type === selectedType) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        categorySelect.value = ''; // Reset selection when type changes
    }

    // Custom select styling
    const select2 = new TomSelect('#category_id', {
        render: {
            option: function(data, escape) {
                const category = categories.find(cat => cat.value === data.value);
                if (!category) return escape(data.text);
                
                return `<div class="d-flex align-items-center">
                    <i class="bi ${category.icon} me-2" style="color: ${category.color}"></i>
                    <span>${escape(category.text)}</span>
                </div>`;
            },
            item: function(data, escape) {
                const category = categories.find(cat => cat.value === data.value);
                if (!category) return escape(data.text);
                
                return `<div class="d-flex align-items-center">
                    <i class="bi ${category.icon} me-2" style="color: ${category.color}"></i>
                    <span>${escape(category.text)}</span>
                </div>`;
            }
        }
    });

    typeSelect.addEventListener('change', filterCategories);
    filterCategories(); // Initial filter
});
</script>
@endpush
@endsection
