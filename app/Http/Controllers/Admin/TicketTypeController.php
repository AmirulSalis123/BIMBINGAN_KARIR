<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TicketType::latest()->paginate(10);
        return view('pages.admin.tiket.index', compact('ticketTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.tiket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_types,name',
        ], [
            'name.required' => 'Nama tipe tiket wajib diisi.',
            'name.max' => 'Nama tipe tiket maksimal 255 karakter.',
            'name.unique' => 'Nama tipe tiket sudah ada.',
        ]);

        TicketType::create($validated);

        return redirect()->route('admin.tikets.index')
            ->with('success', 'Tipe tiket berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketType $tiket)
    {
        return view('pages.admin.tiket.edit', compact('tiket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketType $tiket)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_types,name,' . $tiket->id,
        ], [
            'name.required' => 'Nama tipe tiket wajib diisi.',
            'name.max' => 'Nama tipe tiket maksimal 255 karakter.',
            'name.unique' => 'Nama tipe tiket sudah ada.',
        ]);

        $tiket->update($validated);

        return redirect()->route('admin.tikets.index')
            ->with('success', 'Tipe tiket berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketType $tiket)
    {
        try {
            $tiket->delete();
            return redirect()->route('admin.tikets.index')
                ->with('success', 'Tipe tiket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.tikets.index')
                ->with('error', 'Tipe tiket tidak dapat dihapus karena masih digunakan!');
        }
    }
}
