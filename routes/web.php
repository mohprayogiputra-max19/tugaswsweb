<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanPenjualanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Awal & Tes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "Hello, World!";
});

// ------------------------------------------------------------------------
// RUTE AUTHENTICATION (LOGIN & LOGOUT)
// ------------------------------------------------------------------------

// Halaman Tampilan Login User
Route::get('/login', function () {
    return view('auth.user_login');
})->name('login');

// Halaman Tampilan Login Admin
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('auth.admin_login');
    })->name('admin.login');
});

// Proses Submit Form Login (POST)
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->name('admin.login.process');

// Proses Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------------------------------------------
// RUTE DASHBOARD TERPROTEKSI (MIDDLEWARE AUTH & ROLE)
// ------------------------------------------------------------------------

// Dashboard User (Hanya bisa diakses jika sudah login & role = user)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard User!</h1>
                <form action='".route('logout')."' method='POST'>"
                    .csrf_field().
                    "<button type='submit'>Logout</button>
                </form>";
    })->name('user.dashboard');
});

// Dashboard Admin (Hanya bisa diakses jika sudah login & role = admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard Admin!</h1>
                <form action='".route('logout')."' method='POST'>"
                    .csrf_field().
                    "<button type='submit'>Logout</button>
                </form>";
    })->name('admin.dashboard');
});

Route::get('/', function () {
    return view('welcome');
});
// Routing menuju Controller
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/laporan', LaporanPenjualanController::class);  