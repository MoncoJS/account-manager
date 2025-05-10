@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0">
            <i class="bi bi-tags text-primary"></i> หมวดหมู่
        </h4>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> เพิ่มหมวดหมู่
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-arrow-up-circle"></i> หมวดหมู่นำเข้า
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($incomeCategories->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-arrow-up-circle text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted">ยังไม่มีหมวดหมู่นำเข้า</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($incomeCategories as $category)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi {{ $category->icon ?? 'bi-tag' }} me-2" 
                                               style="color: {{ $category->color ?? '#28a745' }}"></i>
                                            {{ $category->name }}
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('categories.edit', $category) }}" 
                                               class="btn btn-outline-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('คุณต้องการลบหมวดหมู่นี้ใช่หรือไม่?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        data-bs-toggle="tooltip" 
                                                        title="ลบ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-arrow-down-circle"></i> หมวดหมู่นำออก
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($expenseCategories->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-arrow-down-circle text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted">ยังไม่มีหมวดหมู่นำออก</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($expenseCategories as $category)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi {{ $category->icon ?? 'bi-tag' }} me-2" 
                                               style="color: {{ $category->color ?? '#dc3545' }}"></i>
                                            {{ $category->name }}
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('categories.edit', $category) }}" 
                                               class="btn btn-outline-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('คุณต้องการลบหมวดหมู่นี้ใช่หรือไม่?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        data-bs-toggle="tooltip" 
                                                        title="ลบ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
@endsection 