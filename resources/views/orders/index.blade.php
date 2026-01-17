<x-layouts.app>
  <section class="max-w-6xl mx-auto py-12 px-6">
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-3xl font-bold border-b-4 border-blue-900 pb-2">Riwayat Pembelian</h1>
    </div>

    <div class="space-y-6">
      @forelse($orders as $order)
        <article class="card lg:card-side bg-base-100 shadow-md border border-gray-200 hover:shadow-lg transition-all duration-300">
          <figure class="lg:w-48 h-48 lg:h-auto overflow-hidden">
            <img
              src="{{ $order->event && $order->event->gambar ? asset('images/events/' . $order->event->gambar) : 'https://placehold.co/400?text=No+Image' }}"
              alt="{{ $order->event?->judul ?? 'Event Dihapus' }}" 
              class="w-full h-full object-cover" 
            />
          </figure>

          <div class="card-body p-6 flex flex-col justify-between">
            <div class="flex flex-col md:flex-row justify-between gap-4">
              <div>
                <div class="badge badge-primary mb-2">Order #{{ $order->id }}</div>
                <h2 class="card-title text-xl font-bold mb-1">{{ $order->event?->judul ?? 'Event Tidak Ditemukan' }}</h2>
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    📅 {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                </div>
              </div>
              
              <div class="text-right">
                <span class="block text-sm text-gray-500">Total Pembayaran</span>
                <div class="font-bold text-2xl text-blue-900">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </div>
              </div>
            </div>

            <div class="card-actions justify-end mt-4">
              <a href="{{ route('orders.show', $order) }}" class="btn btn-primary bg-blue-900 text-white btn-sm px-6">
                Lihat Detail Tiket
              </a>
            </div>
          </div>
        </article>
      @empty
        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
            <div class="text-6xl mb-4">🎫</div>
            <h3 class="text-lg font-bold">Belum ada pesanan</h3>
            <p class="text-gray-500 mb-4">Yuk, cari event seru dan beli tiket pertamamu!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Cari Event</a>
        </div>
      @endforelse
    </div>
  </section>
</x-layouts.app>