<x-layouts.admin title="History Pembelian">
    <div class="container mx-auto p-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">History Pembelian</h1>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Event</th>
                        <th>Tanggal Pembelian</th>
                        <th>Total Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $index => $history)
                    <tr class="hover">
                        <th>{{ $index + 1 }}</th>
                        <td class="font-medium">{{ $history->user->name }}</td>
                        <td>
                            @if($history->event)
                                <span class="badge badge-ghost">{{ $history->event->judul }}</span>
                            @else
                                <span class="text-gray-400 italic">Event dihapus</span>
                            @endif
                        </td>
                        <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                        <td class="font-bold text-green-600">
                            Rp {{ number_format($history->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.histories.show', $history->id) }}" class="btn btn-sm btn-info text-white">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-700">Belum Ada History</h3>
                                <p class="text-gray-500 mt-2">Mulai dengan menambahkan history baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
