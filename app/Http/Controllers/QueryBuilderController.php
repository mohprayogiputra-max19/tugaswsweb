<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class QueryBuilderController extends Controller
{
    public function demo()
    {
        return response()->json($this->getDemoData());
    }

    public function demoPage()
    {
        $data = $this->getDemoData();

        return view('query-demo', [
            'names' => $data['pluck'],
            'aggregates' => $data['aggregates'],
            'join' => $data['join'],
            'top_ten' => $data['top_ten'],
            'offset_page_two' => $data['offset_page_two'],
            'subquery' => $data['subquery'],
            'raw_sql' => $data['raw_sql'],
        ]);
    }

    private function getDemoData(): array
    {
        $names = DB::table('users')->pluck('name');
        $usersByEmail = DB::table('users')->pluck('name', 'email');

        $aggregates = [
            'total_users' => DB::table('users')->count(),
            'max_salary' => DB::table('employees')->max('salary'),
            'min_salary' => DB::table('employees')->min('salary'),
        ];

        $usersWithOrders = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.name', 'orders.total_price')
            ->get();

        $userColumns = ['id', 'name', 'email', 'role', 'phone'];
        $sortedUsers = DB::table('users')->select($userColumns)->orderBy('name')->get();
        $topTen = DB::table('users')->select($userColumns)->limit(10)->get();
        $pageTwo = DB::table('users')->select($userColumns)->offset(10)->limit(10)->get();

        $usersWithOrderCount = DB::table('users')
            ->select('name')
            ->selectSub(function ($query) {
                $query->from('orders')
                    ->selectRaw('count(*)')
                    ->whereColumn('orders.user_id', 'users.id');
            }, 'order_count')
            ->get();

        $usersCountByRole = DB::table('users')
            ->selectRaw('COUNT(*) as total_users, role')
            ->groupBy('role')
            ->get();

        return [
            'pluck' => $names,
            'pluck_by_email' => $usersByEmail,
            'aggregates' => $aggregates,
            'join' => $usersWithOrders,
            'sorted_users' => $sortedUsers,
            'top_ten' => $topTen,
            'offset_page_two' => $pageTwo,
            'subquery' => $usersWithOrderCount,
            'raw_sql' => $usersCountByRole,
        ];
    }

    public function store() {
    DB::table('users')->insert([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password123') // bcrypt digunakan untuk mengenkripsi password biar aman
    ]);


        $id = DB::table('users')->insertGetId([
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'password' => bcrypt('password123')
        ]);
    }

    public function index() {
    $allUsers = DB::table('users')->get();
    $singleUser = DB::table('users')->where('email', 'johndoe@example.com')->first();
    $selectedUsers = DB::table('users')->select('id', 'name')->get();
    $adminUsers = DB::table('users')
                    ->where('status', 'active')
                    ->where('role', 'admin')
                    ->get();

    $adultUsers = DB::table('users')->where('age', '>=', 18)->get();
}

public function update() {
    DB::table('users')
        ->where('email', 'johndoe@example.com')
        ->update(['status' => 'inactive']);
    DB::table('users')->where('id', 1)->increment('points', 10);
    DB::table('users')->where('id', 1)->decrement('points', 5);
}

public function destroy() {
    // delete() : Menghapus baris data spesifik berdasarkan kondisi where().
    DB::table('users')->where('email', 'johndoe@example.com')->delete();
    DB::table('users')->truncate();
}

public function getSpecificColumns() {
    $names = DB::table('users')->pluck('name');
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

    $allUsersAndOrders = DB::table('users')
        ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
        ->get();
}

public function displayData() {
    // orderBy() : Mengurutkan data. 'asc' dari A ke Z (atau terkecil ke terbesar), 'desc' untuk Z ke A.
    $sortedUsers = DB::table('users')->orderBy('name', 'asc')->get();
    $topTen = DB::table('users')->limit(10)->get();
    $pageTwo = DB::table('users')->offset(10)->limit(10)->get();
}

public function getSubqueryData() {
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
    $usersCountByStatus = DB::table('users')
        ->selectRaw('COUNT(*) as total_users, status')
        ->groupBy('status')
        ->get();

    $filteredUsers = DB::table('users')
        ->whereRaw('age > ? AND status = ?', [18, 'active'])
        ->get();
}

}