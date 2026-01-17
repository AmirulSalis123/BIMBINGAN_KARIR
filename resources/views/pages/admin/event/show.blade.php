<x-layouts.admin title="Detail Event">
    <div class="container mx-auto">
        @if (session('success'))
            <div class="toast toast-top toast-end z-50">
                <div class="alert alert-success text-white">
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            <script>setTimeout(() => { document.querySelector('.toast')?.remove() }, 3000)</script>
        @endif

        <div class="card bg-white shadow-sm mb-10">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Detail Event</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label font-semibold">Judul Event</label>
                            <input type="text" class="input input-bordered w-full bg-gray-100" value="{{ $event->judul }}" disabled />
                        </div>

                        <div class="form-control">
                            <label class="label font-semibold">Kategori</label>
                            <input type="text" class="input input-bordered w-full bg-gray-100" value="{{ $event->kategori->nama }}" disabled />
                        </div>

                        <div class="form-control">
                            <label class="label font-semibold">Tanggal & Waktu</label>
                            <input type="text" class="input input-bordered w-full bg-gray-100" value="{{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('d M Y, H:i') }}" disabled />
                        </div>

                        <div class="form-control">
                            <label class="label font-semibold">Lokasi</label>
                            <input type="text" class="input input-bordered w-full bg-gray-100" value="{{ $event->lokasi }}" disabled />
                        </div>
                        
                        <div class="form-control">
                            <label class="label font-semibold">Deskripsi</label>
                            <textarea class="textarea textarea-bordered h-24 bg-gray-100" disabled>{{ $event->deskripsi }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="label font-semibold">Poster Event</label>
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ asset('images/events/' . $event->gambar) }}" alt="Poster" class="w-full h-auto object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="card-title text-2xl">Daftar Tiket</h2>
                    <button onclick="add_ticket_modal.showModal()" class="btn btn-primary">+ Tambah Tiket</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th>No</th>
                                <th>Tipe Tiket</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $index => $ticket)
                                <tr class="hover">
                                    <th>{{ $index + 1 }}</th>
                                    <td class="font-bold uppercase">{{ $ticket->tipe }}</td>
                                    <td>Rp {{ number_format($ticket->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $ticket->stok > 0 ? 'badge-success' : 'badge-error' }} text-white">
                                            {{ $ticket->stok }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning btn-outline mr-2" 
                                            onclick="openEditModal(this)"
                                            data-id="{{ $ticket->id }}" 
                                            data-tipe="{{ $ticket->tipe }}"
                                            data-harga="{{ $ticket->harga }}"
                                            data-stok="{{ $ticket->stok }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-error btn-outline" 
                                            onclick="openDeleteModal(this)"
                                            data-id="{{ $ticket->id }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">
                                        Belum ada tiket untuk event ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="add_ticket_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Tiket Baru</h3>
            <form method="POST" action="{{ route('admin.tickets.store') }}">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Tipe Tiket</label>
                    <select name="tipe" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Pilih Tipe</option>
                        <option value="Regular">Regular</option>
                        <option value="VIP">VIP</option>
                        <option value="VVIP">VVIP</option>
                    </select>
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Harga (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 50000" class="input input-bordered w-full" required min="0" />
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Stok Tiket</label>
                    <input type="number" name="stok" placeholder="Contoh: 100" class="input input-bordered w-full" required min="0" />
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="add_ticket_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="edit_ticket_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Tiket</h3>
            <form method="POST" id="editTicketForm">
                @csrf
                @method('PUT')

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Tipe Tiket</label>
                    <select name="tipe" id="edit_tipe" class="select select-bordered w-full" required>
                        <option value="Regular">Regular</option>
                        <option value="VIP">VIP</option>
                        <option value="VVIP">VVIP</option>
                    </select>
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Harga (Rp)</label>
                    <input type="number" name="harga" id="edit_harga" class="input input-bordered w-full" required min="0" />
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold">Stok Tiket</label>
                    <input type="number" name="stok" id="edit_stok" class="input input-bordered w-full" required min="0" />
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="edit_ticket_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600 mb-4">Hapus Tiket</h3>
            <p>Apakah Anda yakin ingin menghapus tiket ini?</p>
            <form method="POST" id="deleteTicketForm" class="mt-4">
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
        function openEditModal(button) {
            const id = button.dataset.id;
            const tipe = button.dataset.tipe;
            const harga = button.dataset.harga;
            const stok = button.dataset.stok;

            const form = document.getElementById('editTicketForm');
            
            document.getElementById("edit_tipe").value = tipe;
            document.getElementById("edit_harga").value = harga;
            document.getElementById("edit_stok").value = stok;

            // Set action URL secara dinamis
            form.action = `{{ url('admin/tickets') }}/${id}`;
            
            edit_ticket_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.getElementById('deleteTicketForm');

            // Set action URL secara dinamis
            form.action = `{{ url('admin/tickets') }}/${id}`;
            
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>