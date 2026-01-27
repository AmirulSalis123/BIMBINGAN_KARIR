<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasis = Lokasi::orderBy('nama', 'asc')->paginate(10);
        return view('pages.admin.lokasi.index', compact('lokasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:lokasis,nama',
        ], [
            'nama.required' => 'Nama tipe lokasi wajib diisi.',
            'nama.max' => 'Nama tipe lokasi maksimal 255 karakter.',
            'nama.unique' => 'Nama tipe lokasi sudah ada.',
        ]);

        Lokasi::create($validated);

        return redirect()
            ->route('admin.lokasis.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:lokasis,nama,' . $lokasi->id,
        ], [
            'nama.required' => 'Nama tipe lokasi wajib diisi.',
            'nama.max' => 'Nama tipe lokasi maksimal 255 karakter.',
            'nama.unique' => 'Nama tipe lokasi sudah ada.',
        ]);

        $lokasi->update($validated);

        return redirect()
            ->route('admin.lokasis.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lokasi $lokasi)
{
    $lokasi->delete();

    return redirect()
        ->route('admin.lokasis.index')
        ->with('success', 'Lokasi berhasil dihapus.');
}
}
