<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>

