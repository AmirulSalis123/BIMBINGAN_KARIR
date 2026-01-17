<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index()
    {
        // Mengambil data order terbaru beserta relasi user dan event agar lebih efisien (Eager Loading)
        $histories = Order::with(['user', 'event'])->latest()->get();
        return view('pages.admin.history.index', compact('histories'));
    }

    /**
     * Menampilkan detail transaksi spesifik.
     */
    public function show(string $id)
    {
        // Mengambil order beserta detail tiketnya
        $order = Order::with(['event', 'detailOrders.tiket', 'user'])->findOrFail($id);
        return view('pages.admin.history.show', compact('order'));
    }
}