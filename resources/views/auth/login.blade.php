
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Inventaris Gudang</title>
</head>
<body>
    <h1>Inventaris Gudang</h1>
    <h2>Login</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                   required>
        </div>

        <label>
            <input type="checkbox" name="remember" value="1">
            Ingat saya
        </label>

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun?
        <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</body>
</html>
