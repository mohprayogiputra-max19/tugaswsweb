<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class QueryBuilderController extends Controller
{
    public function store() {
    // FUNGSI: Menyimpan data baru ke tabel 'users'.
    // Kapan dipakai? Saat user klik tombol "Submit" di form pendaftaran.
    DB::table('users')->insert([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password123') // bcrypt digunakan untuk mengenkripsi password biar aman
    ]);

    // FUNGSI: Sama seperti insert, tapi langsung me-return ID dari data yang baru saja masuk.
    // Kapan dipakai? Saat kamu butuh ID user ini untuk dihubungkan ke tabel lain (misal: tabel profil atau transaksi).
        $id = DB::table('users')->insertGetId([
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'password' => bcrypt('password123')
        ]);
    }

    public function index() {
    // get() : Mengambil SEMUA data di tabel users. Hasilnya berupa array/kumpulan data (Collection).
    $allUsers = DB::table('users')->get();

    // first() : Mengambil HANYA 1 data pertama yang cocok dengan kondisi where(). Hasilnya berupa 1 objek (bukan array).
    $singleUser = DB::table('users')->where('email', 'johndoe@example.com')->first();

    // select() : Mengambil data, tapi HANYA kolom 'id' dan 'name' saja. 
    // Fungsinya untuk menghemat memori jika tabelnya punya puluhan kolom tapi kita cuma butuh namanya.
    $selectedUsers = DB::table('users')->select('id', 'name')->get();

    // where() bertumpuk : Mencari data dengan filter spesifik. Logikanya adalah "status = active DAN role = admin".
    $adminUsers = DB::table('users')
                    ->where('status', 'active')
                    ->where('role', 'admin')
                    ->get();

    // where() dengan operator : Mengambil data user yang umurnya lebih dari atau sama dengan 18.
    $adultUsers = DB::table('users')->where('age', '>=', 18)->get();
}

public function update() {
    // update() : Mengubah isi data yang sudah ada. 
    // WAJIB pakai where() bray! Kalau tidak pakai where, SEMUA data di tabel bakal berubah statusnya.
    DB::table('users')
        ->where('email', 'johndoe@example.com')
        ->update(['status' => 'inactive']);

    // increment() : Jalan pintas untuk MENAMBAH angka secara matematis tanpa narik data dulu.
    // decrement() : Kebalikannya, untuk MENGURANGI. 
    // Contoh nyata: Menambah saldo dompet, menambah stok barang, atau mengurangi HP musuh di game.
    DB::table('users')->where('id', 1)->increment('points', 10);
    DB::table('users')->where('id', 1)->decrement('points', 5);
}

public function destroy() {
    // delete() : Menghapus baris data spesifik berdasarkan kondisi where().
    DB::table('users')->where('email', 'johndoe@example.com')->delete();

    // truncate() : MENGHAPUS TOTAL seluruh isi tabel dan me-reset ID kembali ke angka 1.
    // HATI-HATI! Jangan pakai ini di aplikasi yang sudah rilis, biasanya cuma dipakai saat testing/reset database.
    DB::table('users')->truncate();
}

public function getSpecificColumns() {
    // pluck() : Mengambil satu kolom saja dan mengubahnya jadi array biasa yang rata (flat array).
    // Cocok banget dipakai buat ngisi opsi dropdown <select> di HTML.
    $names = DB::table('users')->pluck('name');

    // pluck('value', 'key') : Menjadikan kolom 'email' sebagai ID/Key, dan 'name' sebagai teksnya.
    $users = DB::table('users')->pluck('name', 'email');
}

public function calculateData() {
    $totalUsers = DB::table('users')->count(); // Menghitung ada berapa baris/jumlah user.
    $totalPoints = DB::table('users')->sum('points'); // Mentotal/menjumlahkan seluruh angka di kolom points.
    $averageAge = DB::table('users')->avg('age'); // Mencari nilai rata-rata umur.
    
    $maxSalary = DB::table('employees')->max('salary'); // Mencari gaji paling tinggi.
    $minSalary = DB::table('employees')->min('salary'); // Mencari gaji paling rendah.
}

public function getJoinedData() {
    // Inner Join : Menggabungkan tabel 'users' dan 'orders'.
    // Data HANYA akan muncul jika user punya order. Kalau user belum pernah order, dia nggak akan tampil.
    $usersWithOrders = DB::table('users')
        ->join('orders', 'users.id', '=', 'orders.user_id')
        ->select('users.name', 'orders.total_price')
        ->get();

    // Left Join : Sama kayak join, tapi kalaupun user BELUM PERNAH order, nama user-nya akan TETAP TAMPIL (nilai order-nya akan null/kosong).
    $allUsersAndOrders = DB::table('users')
        ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
        ->get();
}

public function displayData() {
    // orderBy() : Mengurutkan data. 'asc' dari A ke Z (atau terkecil ke terbesar), 'desc' untuk Z ke A.
    $sortedUsers = DB::table('users')->orderBy('name', 'asc')->get();

    // limit() : Cuma ngambil 10 data. (Berguna untuk fitur "Top 10 Leaderboard").
    $topTen = DB::table('users')->limit(10)->get();

    // offset() : "Lompati 10 data pertama, lalu ambil 10 data berikutnya".
    // Ini adalah rumus dasar dibalik fitur Pagination (Halaman 1, 2, 3) di website.
    $pageTwo = DB::table('users')->offset(10)->limit(10)->get();
}

public function getSubqueryData() {
    // selectSub() : Kita mau nampilin nama user, tapi di saat yang sama kita bikin query kecil di dalamnya untuk menghitung total pesanan user tersebut, lalu kita kasih nama alias 'order_count'.
    $users = DB::table('users')
        ->select('name')
        ->selectSub(function ($query) {
            $query->from('orders')
                  ->selectRaw('count(*)')
                  ->whereColumn('orders.user_id', 'users.id');
        }, 'order_count')
        ->get();
}

public function rawSql() {
    // selectRaw() : Memasukkan kode SQL murni langsung ke dalam select.
    $usersCountByStatus = DB::table('users')
        ->selectRaw('COUNT(*) as total_users, status')
        ->groupBy('status')
        ->get();

    // whereRaw() : Kondisi murni SQL. 
    // PENTING BRAY: Selalu gunakan tanda tanya (?) untuk masukin nilainya (parameter binding). Jangan pernah masukin variabel langsung ke dalam string SQL biar website kamu nggak kena hack (SQL Injection).
    $filteredUsers = DB::table('users')
        ->whereRaw('age > ? AND status = ?', [18, 'active'])
        ->get();
}

}