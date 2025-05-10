<!DOCTYPE html>
<html>
<head>
    <title>Account Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Account Manager</a>
        <div class="collapse navbar-collapse justify-content-end">
            <a href="{{ route('dashboard') }}" class="nav-link text-white">Dashboard</a>
            <a href="{{ route('bank-accounts.index') }}" class="nav-link text-white">บัญชีธนาคาร</a>
            <a href="{{ route('transactions.index') }}" class="nav-link text-white">รายรับรายจ่าย</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
