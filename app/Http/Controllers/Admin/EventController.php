<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Menampilkan daftar event
    public function index()
    {
        $events = Event::with('kategori')->latest()->get();
        return view('pages.admin.event.index', compact('events'));
    }

    // Form tambah event
    public function create()
    {
        $categories = Kategori::all();
        return view('pages.admin.event.create', compact('categories'));
    }

    // Simpan event baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal_waktu' => 'required|date',
            'lokasi'        => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'gambar'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle Upload Gambar
        if ($request->hasFile('gambar')) {
            // Simpan gambar di folder 'public/images/events'
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/events'), $imageName);
            $validatedData['gambar'] = $imageName;
        }

        // Tambahkan user_id (Admin yang buat)
        $validatedData['user_id'] = Auth::id();

        Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    // Tampilkan detail event
    public function show(string $id)
    {
        $event = Event::with(['kategori', 'tikets'])->findOrFail($id);
        $categories = Kategori::all();
        $tickets = $event->tikets; // Ambil tiket terkait jika ada

        return view('pages.admin.event.show', compact('event', 'categories', 'tickets'));
    }

    // Form edit event
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        return view('pages.admin.event.edit', compact('event', 'categories'));
    }

    // Update event
    public function update(Request $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);

            $validatedData = $request->validate([
                'judul'         => 'required|string|max:255',
                'deskripsi'     => 'required|string',
                'tanggal_waktu' => 'required|date',
                'lokasi'        => 'required|string|max:255',
                'kategori_id'   => 'required|exists:kategoris,id',
                'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Cek jika ada gambar baru
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada (opsional, tapi disarankan agar server tidak penuh)
                if ($event->gambar && file_exists(public_path('images/events/' . $event->gambar))) {
                    unlink(public_path('images/events/' . $event->gambar));
                }

                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('images/events'), $imageName);
                $validatedData['gambar'] = $imageName;
            }

            $event->update($validatedData);

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    // Hapus event
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        
        // Hapus gambar fisik dari folder
        if ($event->gambar && file_exists(public_path('images/events/' . $event->gambar))) {
            unlink(public_path('images/events/' . $event->gambar));
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}