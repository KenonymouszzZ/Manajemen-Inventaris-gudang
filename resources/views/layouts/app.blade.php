<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventaris Gudang')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
</head>
<body>
<div class="app-layout">
    @auth
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-mark">IG</span>
            <span><strong>GudangKu</strong><small>Inventory Management</small></span>
        </a>
        <div class="sidebar-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">▦</span> Dashboard</a>
            <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*','products.*') ? 'active' : '' }}"><span class="nav-icon">▣</span> Inventaris</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><span class="nav-icon">▤</span> Kategori</a>
            <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><span class="nav-icon">⇄</span> Transaksi Stok</a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"><span class="nav-icon">▥</span> Laporan Inventaris</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="user-mini"><span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ ucfirst(auth()->user()->role) }}</small></span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-link" type="submit">↪ Keluar</button></form>
        </div>
    </aside>
    @endauth
    <main class="main-area">
        @auth
        <header class="topbar"><div><span class="topbar-kicker">SISTEM MANAJEMEN GUDANG</span><strong>@yield('title', 'Dashboard')</strong></div><div class="topbar-right"><span class="status-dot"></span><span>Sistem aktif</span><span class="top-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span></div></header>
        @endauth
        <div class="content-area">
            @yield('content')
        </div>
        <footer class="footer">© {{ date('Y') }} GudangKu · Sistem Manajemen Inventaris</footer>
    </main>
</div>
</body>
</html>
