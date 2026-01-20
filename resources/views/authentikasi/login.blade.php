<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Aplikasi Pelaporan</h2>

@if($errors->any())
    <div style="color:red">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="/login">
    @csrf

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

</body>
</html>
