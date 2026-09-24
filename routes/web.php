<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanPenjualanController;
// Wajib ditaruh di atas sini ya brayy import-nya!
use App\Http\Controllers\UserController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal (Bawaan Laravel) - Cukup tulis satu kali saja
Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "Hello, World!";
});

// ------------------------------------------------------------------------
// CRUD USERS
// ------------------------------------------------------------------------
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


// ------------------------------------------------------------------------
// RUTE AUTHENTICATION (LOGIN & LOGOUT)
// ------------------------------------------------------------------------
Route::get('/login', function () {
    return view('auth.user_login');
})->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('auth.admin_login');
    })->name('admin.login');
});

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->name('admin.login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ------------------------------------------------------------------------
// RUTE DASHBOARD TERPROTEKSI (MIDDLEWARE AUTH & ROLE)
// ------------------------------------------------------------------------
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard User!</h1>
                <form action='".route('logout')."' method='POST'>"
                    .csrf_field().
                    "<button type='submit'>Logout</button>
                </form>";
    })->name('user.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard Admin!</h1>
                <form action='".route('logout')."' method='POST'>"
                    .csrf_field().
                    "<button type='submit'>Logout</button>
                </form>";
    })->name('admin.dashboard');
});


// ------------------------------------------------------------------------
// RUTE PRODUK & LAPORAN
// ------------------------------------------------------------------------
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/laporan', LaporanPenjualanController::class);