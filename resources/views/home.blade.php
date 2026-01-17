<x-layouts.app>
    <div class="hero bg-blue-900 min-h-[400px] relative overflow-hidden">
        <div class="hero-content text-center text-white relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-extrabold mb-4 leading-tight">
                    Temukan Event Seru,<br>Amankan Tiketmu!
                </h1>
                <p class="py-6 text-xl text-blue-100">
                    Platform pembelian tiket event termudah dan terpercaya. 
                    Dari konser musik hingga seminar inspiratif, semua ada di BengTix.
                </p>
                <a href="#event-section" class="btn btn-warning btn-lg px-8 rounded-full">Cari Event Sekarang</a>
            </div>
        </div>
    </div>

    <section id="event-section" class="max-w-7xl mx-auto py-12 px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-2xl font-black uppercase italic text-gray-800 border-l-4 border-blue-900 pl-3">
                Jelajahi Event
            </h2>
            
            <div class="flex flex-wrap gap-2 justify-center md:justify-end">
                <a href="{{ route('home') }}">
                    <x-user.category-pill :label="'Semua'" :active="!request('kategori')" />
                </a>
                @foreach($categories as $kategori)
                <a href="{{ route('home', ['kategori' => $kategori->id]) }}">
                    <x-user.category-pill 
                        :label="$kategori->nama" 
                        :active="request('kategori') == $kategori->id" 
                    />
                </a>
                @endforeach
            </div>
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <x-user.event-card 
                        :href="route('events.show', $event->id)"
                        :title="$event->judul" 
                        :date="$event->tanggal_waktu" 
                        :location="$event->lokasi"
                        :price="$event->tikets_min_harga" 
                        :image="$event->gambar" 
                        :href="route('events.show', $event)"
                    />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">😢</div>
                <h3 class="text-xl font-bold text-gray-600">Yah, Event tidak ditemukan.</h3>
                <p class="text-gray-500">Coba pilih kategori lain atau kembali lagi nanti.</p>
                <a href="{{ route('home') }}" class="btn btn-outline mt-4">Lihat Semua Event</a>
            </div>
        @endif
        
    </section>
</x-layouts.app>