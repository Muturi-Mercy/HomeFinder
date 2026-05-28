<!DOCTYPE html>
<html>
<head>
    <title>HomeFinder - Tenant Register</title>
</head>
<body>
    <h2>Create Tenant Account</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Full Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/login">Login here</a></p>
</body>
</html>