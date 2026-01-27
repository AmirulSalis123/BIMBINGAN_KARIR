<x-layouts.admin title="Manajemen Event">
    @if (session('success'))
        <div class="toast toast-top toast-end z-50">
            <div class="alert alert-success text-white">
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.querySelector('.toast');
                if(toast) toast.remove();
            }, 3000)
        </script>
    @endif

    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Event</h1>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Event
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-16">No</th>
                        <th class="w-20">Poster</th>
                        <th>Judul Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th class="w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $index => $event)
                        <tr class="hover">
                            <th>{{ $index + 1 }}</th> <!-- Diubah dari firstItem() ke index+1 -->
                            <td>
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden">
                                        @if($event->gambar && file_exists(public_path('images/events/' . $event->gambar)))
                                            <img src="{{ asset('images/events/' . $event->gambar) }}"
                                                 alt="{{ $event->judul }}"
                                                 class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold">{{ $event->judul }}</td>
                            <td>
                                <span class="badge badge-outline badge-primary">
                                    {{ $event->kategori->nama ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('H:i') }}
                                </div>
                            </td>
                            <td class="text-sm">
                                @if($event->lokasi)
                                    {{ Str::limit($event->lokasi->nama, 30) }}
                                @else
                                    <span class="text-gray-400">Belum ada lokasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.events.show', $event->id) }}"
                                       class="btn btn-sm btn-success btn-outline"
                                       title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}"
                                       class="btn btn-sm btn-warning btn-outline"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button class="btn btn-sm btn-error btn-outline"
                                            onclick="openDeleteModal('{{ $event->id }}')"
                                            title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="text-xl font-semibold text-gray-700">Belum Ada Event</h3>
                                <p class="text-gray-500 mt-2">Mulai dengan menambahkan event baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>

    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600 mb-4">Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form method="POST" id="deleteForm" class="mt-6">
                @csrf
                @method('DELETE')

                <div class="modal-action">
                    <button type="button" class="btn" onclick="delete_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-error text-white">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `{{ url('admin/events') }}/${id}`;
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
