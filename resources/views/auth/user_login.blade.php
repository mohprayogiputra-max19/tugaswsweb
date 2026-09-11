<form action="/login" method="POST">
    @csrf
    <h2>Login Pengguna</h2>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Masuk</button>
</form>