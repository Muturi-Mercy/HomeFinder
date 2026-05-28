<!DOCTYPE html>
<html>
<head>
    <title>HomeFinder - Tenant Login</title>
</head>
<body>
    <h2>Tenant Login</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p>No account? <a href="/register">Register here</a></p>
    <p>Are you a landlord? <a href="/landlord/login">Landlord Login</a></p>
</body>
</html>