<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'total_harga',
        'order_date', // <--- GANTI 'tanggal_order' JADI INI (Sesuai nama di Database & Controller)
        'status'
    ];

    protected $casts = [
        'order_date' => 'datetime', // <--- INI JUGA DISESUAIKAN
    ];

    // Relasi ke User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke Detail Order (Rincian Tiket)
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}