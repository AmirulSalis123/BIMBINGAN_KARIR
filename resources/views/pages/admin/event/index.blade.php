<x-layouts.admin title="Manajemen Event">
    @if (session('success'))
        <div class="toast toast-top toast-end z-50">
            <div class="alert alert-success text-white">
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>setTimeout(() => { document.querySelector('.toast')?.remove() }, 3000)</script>
    @endif

    <div class="container mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Event</h1>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">+ Tambah Event</a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th>No</th>
                        <th>Poster</th>
                        <th>Judul Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $index => $event)
                    <tr class="hover">
                        <th>{{ $index + 1 }}</th>
                        <td>
                            <div class="avatar">
                                <div class="w-12 h-12 rounded">
                                    <img src="{{ asset('images/events/' . $event->gambar) }}" alt="{{ $event->judul }}" />
                                </div>
                            </div>
                        </td>
                        <td class="font-bold">{{ $event->judul }}</td>
                        <td><span class="badge badge-ghost">{{ $event->kategori->nama }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('d M Y, H:i') }}</td>
                        <td>{{ $event->lokasi }}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-sm btn-info btn-square" title="Detail">
                                    ℹ️
                                </a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning btn-square" title="Edit">
                                    ✏️
                                </a>
                                <button class="btn btn-sm btn-error btn-square text-white" onclick="openDeleteModal('{{ $event->id }}')" title="Hapus">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">Belum ada event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
            <p class="py-4">Apakah Anda yakin ingin menghapus event ini?</p>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="button" class="btn" onclick="delete_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-error text-white">Hapus</button>
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