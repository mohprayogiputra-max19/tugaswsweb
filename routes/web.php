<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'kasir' => redirect()->route('kasir.dashboard'),
        default => abort(403, 'Role akun tidak dikenali.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/role-check', fn () => 'Halaman dengan middleware parameter role.')
        ->middleware('cek.role:admin')
        ->name('admin.role-check');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', fn () => redirect()->route('admin.dashboard'))->name('admin.home');
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/products', [ProdukController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [ProdukController::class, 'show'])->name('products.show');
        Route::get('/reports', LaporanPenjualanController::class)->name('reports.index');
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir/dashboard', [KasirController::class, 'dashboard'])->name('kasir.dashboard');
    });

    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('transaksi', TransactionController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
