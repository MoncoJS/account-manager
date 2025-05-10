@extends('layouts.app')

@section('content')
<h1>ภาษีปี {{ $year }}</h1>
<p>รายได้ทั้งหมด: {{ number_format($totalIncome,2) }} บาท</p>
<p>ภาษีที่ต้องจ่าย: {{ number_format($tax,2) }} บาท</p>
<a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
@endsection
