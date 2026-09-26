<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\QueryBuilderController;
use App\Http\Controllers\TransactionController; 

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
// CRUD USERS (Acara 17-18)
// ------------------------------------------------------------------------
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
// Perbaikan Standar: Rute edit biasanya membutuhkan ID, pastikan sesuai dengan Controller Anda
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
// Di sinilah kita mengintegrasikan file user.blade.php dan admin.blade.php

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        // MODIFIKASI: Sebelumnya me-return HTML manual, sekarang memanggil file user.blade.php
        return view('user'); 
    })->name('user.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        // MODIFIKASI: Memanggil file admin.blade.php agar navigasinya muncul
        return view('admin'); 
    })->name('admin.dashboard');
});


// ------------------------------------------------------------------------
// RUTE TRANSAKSI / DOMPET PRIBADI (Acara 19-20)
// ------------------------------------------------------------------------
// TAMBAHAN: Rute untuk menampilkan data dan menghapus (Soft Delete) transaksi
Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');


// ------------------------------------------------------------------------
// RUTE PRODUK & LAPORAN
// ------------------------------------------------------------------------
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/laporan', LaporanPenjualanController::class);

// Demo query builder: hanya membaca data dan mengembalikan JSON.
Route::get('/query-demo', [QueryBuilderController::class, 'demo'])
    ->name('query.demo');
Route::get('/query-demo/page', [QueryBuilderController::class, 'demoPage'])
    ->name('query.demo.page');

// Route untuk menangani pengiriman data validasi form (Implementasi Acara 20)
Route::post('/users/submit', [UserController::class, 'submitForm']);