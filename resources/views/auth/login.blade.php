<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Smart Exam</title>
</head>
<body>
    <div style="max-width: 400px; margin: 100px auto; border: 1px solid #ccc; padding: 20px;">
        <h2>Login</h2>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div>
                <label>Email</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <br>
            <div>
                <label>Password</label><br>
                <input type="password" name="password" required>
            </div>
            <br>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>