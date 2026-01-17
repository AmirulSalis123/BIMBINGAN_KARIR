<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Menampilkan Riwayat Pembelian
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('detailOrders.tiket', 'event');
        return view('orders.show', compact('order'));
    }

    // Memproses Order Baru (AJAX)
    public function store(Request $request)
    {
        //Controller memvalidasi input. Sistem memastikan event_id ada, dan items memiliki format array yang valid serta jumlahnya minimal 1.
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'items'    => 'required|array|min:1',
            'items.*.tiket_id' => 'required|integer|exists:tikets,id',
            'items.*.jumlah'   => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        try {
            //Saya membungkus seluruh proses penyimpanan di dalam DB::transaction. Tujuannya agar atomicity terjaga. Artinya, jika terjadi error saat simpan detail tiket, maka order utama juga akan dibatalkan, sehingga tidak ada data sampah.
            $order = DB::transaction(function () use ($data, $user) {
                $total = 0;

                // Cek Stok & Hitung Total Dulu
                foreach ($data['items'] as $it) {
                    //Fungsinya untuk mengunci baris data tiket tersebut di database sementara waktu. Jika ada dua user membeli tiket terakhir secara bersamaan, sistem akan memprosesnya antrean satu per satu untuk mencegah stok menjadi minus.
                    $t = Tiket::lockForUpdate()->findOrFail($it['tiket_id']);
                    
                    if ($t->stok < $it['jumlah']) {
                        throw new \Exception("Stok tidak cukup untuk tiket: {$t->tipe}");
                    }
                    $total += ($t->harga ?? 0) * $it['jumlah'];
                }

                // Buat Order Utama
                $order = Order::create([
                    'user_id'     => $user->id,
                    'event_id'    => $data['event_id'],
                    'order_date'  => Carbon::now(),
                    'total_harga' => $total,
                    'status'      => 'success', 
                ]);

                // Simpan Detail & Kurangi Stok
                foreach ($data['items'] as $it) {
                    $t = Tiket::findOrFail($it['tiket_id']);
                    $subtotal = ($t->harga ?? 0) * $it['jumlah'];

                    DetailOrder::create([
                        'order_id'       => $order->id,
                        'tiket_id'       => $t->id,
                        'jumlah'         => $it['jumlah'],
                        'subtotal_harga' => $subtotal,
                    ]);

                    // Kurangi Stok
                    $t->stok = max(0, $t->stok - $it['jumlah']);
                    $t->save();
                }

                return $order;
            });

            // 3. Response Sukses
            session()->flash('success', 'Pesanan berhasil dibuat! Terima kasih.');
            
            return response()->json([
                'ok' => true, 
                'order_id' => $order->id, 
                'redirect' => route('orders.index')
            ]);

        } catch (\Exception $e) {
            // 4. Response Gagal
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }
}