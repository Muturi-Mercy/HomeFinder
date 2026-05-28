<!DOCTYPE html>
<html>
<head>
    <title>HomeFinder - Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/admin/login">
        @csrf
        <input type="email" name="email" placeholder="Admin Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>