<x-layouts.admin title="Detail Pemesanan">
    <section class="max-w-4xl mx-auto py-12 px-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pemesanan</h1>
            <div class="text-sm text-gray-500">
                Order #{{ $order->id }} • {{ $order->created_at->format('d M Y H:i') }}
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl overflow-hidden">
            <div class="lg:flex">
                
                <div class="lg:w-1/3 p-0 relative">
                    <img
                        src="{{ $order->event && $order->event->gambar ? asset('images/events/' . $order->event->gambar) : 'https://placehold.co/400x600?text=No+Image' }}"
                        alt="{{ $order->event?->judul ?? 'Event Deleted' }}" 
                        class="w-full h-full object-cover absolute inset-0" 
                        style="min-height: 300px;"
                    />
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-white">
                        <h2 class="font-bold text-lg leading-tight">{{ $order->event?->judul ?? 'Event Tidak Ditemukan' }}</h2>
                        <p class="text-sm opacity-90 mt-1 flex items-center gap-1">
                            📍 {{ $order->event?->lokasi ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="card-body lg:w-2/3 bg-white">
                    <h3 class="font-bold text-lg mb-4 text-gray-700 border-b pb-2">Rincian Tiket</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->detailOrders as $detail)
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $detail->tiket->tipe ?? 'Tiket Dihapus' }}</div>
                                    <div class="text-sm text-gray-500">Qty: {{ $detail->jumlah }}x</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-gray-800">Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="divider my-4"></div>

                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-2xl text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="text-sm text-gray-500 mb-1">Pembeli</div>
                        <div class="font-bold text-gray-800">{{ $order->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                    </div>

                    <div class="card-actions justify-end mt-auto">
                        <a href="{{ route('admin.histories.index') }}" class="btn btn-outline">
                            ← Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.admin>