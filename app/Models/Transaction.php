<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Transaction extends Model
{
    use SoftDeletes; // Aktifkan fitur hapus aman

    // 1. MASS ASSIGNMENT: Mengamankan input form (Acara 19)
    protected $fillable = ['user_id', 'jenis', 'nominal', 'keterangan'];
    protected $dates = ['deleted_at'];

    // 2. RELATIONSHIP: Relasi ke tabel User (Acara 19)
    // Penjelasan: "Setiap transaksi adalah milik (belongsTo) satu User"
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. ACCESSOR: Memformat angka menjadi Rupiah otomatis (Acara 19)
    public function getNominalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // 4. QUERY SCOPE: Jalan pintas mencari data 'Pemasukan' (Acara 19)
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }
}