<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Praktikum Pengantar API Laravel: REST API Books

Praktikum ini membuat REST API untuk mengelola data buku (CRUD). Jalankan migrasi
terlebih dahulu dari terminal proyek:

```bash
php artisan migrate
```

Semua endpoint menggunakan prefix `/api` dan menerima/mengembalikan JSON.
Data buku terdiri dari `title` (string), `description` (string), dan `status`
(integer nonnegatif). Untuk membuat buku, ketiga field wajib diisi.

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/api/books` | Menampilkan semua buku |
| POST | `/api/books` | Menambahkan buku |
| GET | `/api/books/{id}` | Menampilkan satu buku |
| PUT/PATCH | `/api/books/{id}` | Memperbarui buku |
| DELETE | `/api/books/{id}` | Menghapus buku |

`PATCH` dapat memperbarui sebagian field, sedangkan `PUT` memerlukan semua
field. Contoh membuat buku dari PowerShell:

```bash
curl.exe -X POST http://127.0.0.1:8000/api/books -H 'Accept: application/json' -H 'Content-Type: application/json' --data-raw '{"title":"Belajar Laravel","description":"Pengantar API Laravel","status":1}'
```

Jalankan server lokal dengan `php artisan serve`. API mengembalikan HTTP `201`
untuk pembuatan data, `200` untuk permintaan berhasil, `404` jika buku tidak
ditemukan, dan `422` jika validasi gagal. Tes fitur memakai SQLite in-memory
agar tidak mengubah database aplikasi; ekstensi PHP `pdo_sqlite` harus tersedia
untuk menjalankannya.

## Praktikum Autentikasi API dengan Sanctum

Proyek ini sudah menggunakan Laravel Sanctum untuk menerbitkan personal access
token. Migrasi `personal_access_tokens` tersedia pada proyek; periksa status
migrasi dengan `php artisan migrate:status` dan jalankan `php artisan migrate`
jika masih ada migrasi yang belum diterapkan.

Endpoint autentikasi menerima dan mengembalikan JSON:

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| POST | `/api/login` | Memeriksa email/password dan membuat Bearer token |
| GET | `/api/user` | Mengambil data pengguna yang sedang terautentikasi |
| POST | `/api/logout` | Menghapus token yang digunakan pada request |
| POST | `/api/logout-all` | Menghapus semua token milik pengguna |

Di Postman, gunakan `http://127.0.0.1:8000` sebagai base URL:

1. Kirim `POST /api/login` dengan Body **raw / JSON** berisi `email` dan
   `password`. Respons sukses memiliki `access_token` dan `token_type: Bearer`.
2. Untuk `GET /api/user` dan logout, tambahkan header
   `Authorization: Bearer <access_token>` dari respons login serta
   `Accept: application/json`.
3. Kirim `POST /api/logout` untuk mengakhiri token saat ini. Token tersebut
   tidak lagi dapat digunakan; token lain milik pengguna tetap aktif. Gunakan
   `POST /api/logout-all` untuk mencabut seluruh token pengguna.

Login mengembalikan `401` untuk kredensial salah, validasi input mengembalikan
`422`, dan endpoint yang memerlukan autentikasi mengembalikan `401` jika Bearer
token tidak ada atau tidak valid. Token hanya dikirim saat login berhasil;
jangan menyimpan atau membagikan token di repository.

`GET /api/user` mengembalikan `id`, `name`, `email`, `is_admin` (`1` untuk
admin, `0` untuk kasir), `email_verified_at`, `role`, `created_at`, dan
`updated_at`. Password dan token tidak termasuk dalam respons data pengguna.
