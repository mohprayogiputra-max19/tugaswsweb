<form action="/admin/login" method="POST">
    @csrf
    <h2>Login Administrator</h2>
    <input type="email" name="email" placeholder="Email Admin" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Masuk sebagai Admin</button>
</form>