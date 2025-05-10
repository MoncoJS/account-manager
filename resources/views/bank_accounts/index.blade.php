@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0">
            <i class="bi bi-bank text-primary"></i> บัญชีธนาคาร
        </h4>
        <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> เพิ่มบัญชี
        </a>
    </div>
    <div class="card-body">
        @if($bankAccounts->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bank text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted h5">ยังไม่มีบัญชีธนาคาร</p>
                <p class="text-muted">เริ่มต้นโดยการเพิ่มบัญชีธนาคารของคุณ</p>
                <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> เพิ่มบัญชีแรก
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">ชื่อธนาคาร</th>
                            <th class="py-3">เลขบัญชี</th>
                            <th class="py-3 text-end">ยอดเริ่มต้น</th>
                            <th class="py-3 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bankAccounts as $account)
                            <tr class="transition">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bank me-2 text-primary"></i>
                                        <span class="fw-medium">{{ $account->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $account->account_number }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-medium">{{ number_format($account->initial_balance, 2) }} บาท</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('bank-accounts.edit', $account) }}" 
                                            class="btn btn-outline-warning btn-sm" 
                                            data-bs-toggle="tooltip" 
                                            title="แก้ไข">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('bank-accounts.destroy', $account) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirm('คุณต้องการลบบัญชีนี้ใช่หรือไม่?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="tooltip" 
                                                title="ลบ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush

<style>
.transition {
    transition: all 0.2s ease-in-out;
}
.transition:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
@endsection
