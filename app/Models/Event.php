<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',          // <--- Ganti 'nama' jadi 'judul' (sesuai form & controller)
        'deskripsi',
        'tanggal_waktu',  // <--- Ganti 'tanggal' jadi 'tanggal_waktu'
        'lokasi',
        'gambar',
        'kategori_id',
        'user_id',        // <--- SUDAH BENAR (Wajib ada)
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime', // Sesuaikan juga casting-nya
    ];
    //Menggunakan hasMany, karena satu event bisa memiliki banyak jenis tiket.
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
    //Menggunakan belongsTo, karena satu event memiliki satu kategori.
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}