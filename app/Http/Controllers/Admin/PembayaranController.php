<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = Pembayaran::latest()->paginate(10);
        return view('pages.admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.pembayaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255|unique:pembayarans,nama_pembayaran',
        ], [
            'nama_pembayaran.required' => 'Nama tipe pembayaran harus diisi',
            'nama_pembayaran.max' => 'Nama tipe pembayaran maksimal 255 karakter',
            'nama_pembayaran.unique' => 'Nama tipe pembayaran sudah ada',
        ]);

        Pembayaran::create($validated);

        return redirect()->route('admin.pembayarans.index')
            ->with('success', 'Tipe pembayaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        return view('pages.admin.pembayaran.edit', compact('pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255|unique:pembayarans,nama_pembayaran,' . $pembayaran->id,
        ], [
            'nama_pembayaran.required' => 'Nama tipe pembayaran harus diisi',
            'nama_pembayaran.max' => 'Nama tipe pembayaran maksimal 255 karakter',
            'nama_pembayaran.unique' => 'Nama tipe pembayaran sudah ada',
        ]);

        $pembayaran->update($validated);

        return redirect()->route('admin.pembayarans.index')
            ->with('success', 'Tipe pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route('admin.pembayarans.index')
            ->with('success', 'Tipe pembayaran berhasil dihapus');
    }
}
