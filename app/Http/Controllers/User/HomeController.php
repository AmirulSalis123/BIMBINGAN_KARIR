<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan Halaman Utama (Homepage)
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter
        $categories = Kategori::all();

        // 2. Query Event dengan harga tiket termurah
        $eventsQuery = Event::withMin('tikets', 'harga')
            ->orderBy('tanggal_waktu', 'asc');

        // 3. Filter jika ada parameter kategori di URL
        if ($request->has('kategori') && $request->kategori) {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        // 4. Eksekusi query
        $events = $eventsQuery->get();

        // 5. Tampilkan view home
        return view('home', compact('events', 'categories'));
    }
}