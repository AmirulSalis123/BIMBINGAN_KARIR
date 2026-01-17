<x-layouts.app>
  <section class="max-w-4xl mx-auto py-12 px-6">
    
    <div class="flex items-center justify-between mb-6">
      <div>
          <h1 class="text-2xl font-bold">Detail Pemesanan</h1>
          <p class="text-sm text-gray-500">Order ID: #{{ $order->id }}</p>
      </div>
      <div class="badge badge-success text-white p-3">Berhasil</div>
    </div>

    <div class="card bg-base-100 shadow-xl border border-gray-100 overflow-hidden">
      <div class="lg:flex">
        <div class="lg:w-1/3 relative min-h-[200px]">
          <img
            src="{{ $order->event && $order->event->gambar ? asset('images/events/' . $order->event->gambar) : 'https://placehold.co/400?text=No+Image' }}"
            alt="{{ $order->event?->judul ?? 'Event' }}" 
            class="w-full h-full object-cover absolute inset-0" 
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
             <div class="text-white">
                 <h2 class="font-bold text-lg leading-tight">{{ $order->event?->judul ?? 'Event' }}</h2>
                 <p class="text-xs opacity-90 mt-1">{{ $order->event?->lokasi ?? '' }}</p>
             </div>
          </div>
        </div>

        <div class="card-body lg:w-2/3 bg-white p-6">
          <h3 class="font-bold text-gray-800 border-b pb-2 mb-3">Rincian Tiket</h3>
          
          <div class="space-y-4">
            @foreach($order->detailOrders as $d)
              <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                <div>
                  <div class="font-bold text-blue-900">{{ $d->tiket->tipe }}</div>
                  <div class="text-xs text-gray-500">Jumlah: {{ $d->jumlah }} tiket</div>
                </div>
                <div class="text-right">
                  <div class="font-bold text-gray-800">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                  <div class="text-xs text-gray-400">@ {{ number_format($d->tiket->harga, 0, ',', '.') }} / tiket</div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="divider my-4"></div>

          <div class="flex justify-between items-center">
            <span class="font-bold text-gray-600">Total Bayar</span>
            <span class="font-bold text-2xl text-blue-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
          </div>
          
          <div class="text-xs text-gray-400 mt-1 text-right">
              Dibayar pada: {{ $order->created_at->translatedFormat('d F Y, H:i') }}
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8 flex justify-between">
      <a href="{{ route('orders.index') }}" class="btn btn-outline">
        ← Kembali ke Riwayat
      </a>
      <a href="{{ route('home') }}" class="btn btn-primary">
        Beli Tiket Lain
      </a>
    </div>
  </section>
</x-layouts.app>