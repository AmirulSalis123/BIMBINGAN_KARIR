<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with events and categories.
     */
  public function index(Request $request)
{
    // Get all categories for the filter pills
    $categories = Kategori::all();

    // Build event query
    $eventsQuery = Event::with(['kategori', 'tikets']);

    // 1. Filter Kategori (Logika Lama)
    if ($request->has('kategori') && $request->kategori) {
        $eventsQuery->where('kategori_id', $request->kategori);
    }

    // 2. LOGIKA BARU: Filter Pencarian (Search)
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $eventsQuery->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Get events with minimum ticket price
    $events = $eventsQuery->get()->map(function ($event) {
        $event->tikets_min_harga = $event->tikets->min('harga') ?? 0;
        return $event;
    });

    return view('home', [
        'categories' => $categories,
        'events' => $events,
    ]);
}
}
