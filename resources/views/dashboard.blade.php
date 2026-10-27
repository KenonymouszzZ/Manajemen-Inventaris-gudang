
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Inventaris Gudang</title>
</head>
<body>
    <h1>Dashboard Inventaris Gudang</h1>

    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    @if (auth()->user()->role === 'admin')
        <h2>Menu Admin</h2>
        <a href="{{ route('admin.index') }}">Kelola Admin</a>
    @endif

    <h2>Menu Inventaris</h2>
    <a href="{{ route('inventory.index') }}">Buka Inventaris</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
