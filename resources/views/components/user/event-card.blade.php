@props(['title', 'date', 'location', 'price', 'image', 'href' => null])

@php
    // Format Harga
    $formattedPrice = $price ? 'Rp ' . number_format($price, 0, ',', '.') : 'Gratis / Harga Belum Tersedia';

    // Format Tanggal
    $formattedDate = $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y, H:i')
        : 'Segera';

    // Logic Gambar: Gunakan gambar default jika kosong
    $imageUrl = $image 
        ? asset('images/events/' . $image) 
        : 'https://placehold.co/400x250?text=No+Image';
@endphp

<a href="{{ $href ?? '#' }}" class="block group">
    <div class="card bg-base-100 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full">
        <figure class="h-48 overflow-hidden">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $title }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
        </figure>

        <div class="card-body p-5">
            <h2 class="card-title text-lg font-bold line-clamp-2 leading-tight">
                {{ $title }}
            </h2>

            <div class="text-sm text-gray-500 flex items-center gap-1 mt-2">
                📅 {{ $formattedDate }}
            </div>

            <div class="text-sm text-gray-500 flex items-center gap-1">
                📍 {{ $location }}
            </div>

            <div class="mt-auto pt-4 border-t border-gray-100">
                <p class="font-bold text-lg text-blue-900">
                    {{ $formattedPrice }}
                </p>
                @if($price)
                    <p class="text-xs text-gray-400">Mulai dari</p>
                @endif
            </div>
        </div>
    </div>
</a>