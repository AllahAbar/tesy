<!-- resources/views/auth/register.blade.php -->
<form method="POST" action="{{ route('loginn') }}">
    @csrf

    <div>
        <label for="username">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    </div>

    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="password">Password</label>
        <input id="password" type="text" name="password" required>
    </div>

    

    <button type="submit">Register</button>
</form>
