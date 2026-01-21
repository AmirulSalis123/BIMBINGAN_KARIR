<x-layouts.admin title="Manajemen Pembayaran">

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

    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Tipe Pembayaran</h1>
            <button class="btn btn-primary" onclick="add_modal.showModal()">
                + Tambah Tipe Pembayaran
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Tipe Pembayaran</th>
                        <th>Tanggal Dibuat</th>
                        <th class="w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayarans as $index => $pembayaran)
                        <tr class="hover">
                            <th>{{ $pembayarans->firstItem() + $index }}</th>
                            <td class="font-medium">{{ $pembayaran->nama_pembayaran }}</td>
                            <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="btn btn-sm btn-warning btn-outline"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $pembayaran->id }}"
                                            data-nama="{{ $pembayaran->nama_pembayaran }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-error btn-outline"
                                            onclick="openDeleteModal(this)"
                                            data-id="{{ $pembayaran->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                Tidak ada data tipe pembayaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembayarans->hasPages())
        <div class="flex justify-center mt-4">
            {{ $pembayarans->links() }}
        </div>
        @endif
    </div>

    <dialog id="add_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Tipe Pembayaran Baru</h3>
            <form method="POST" action="{{ route('admin.pembayarans.store') }}">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Tipe Pembayaran</span>
                    </label>
                    <input type="text" name="nama_pembayaran" placeholder="Contoh: Transfer Bank" class="input input-bordered w-full" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="add_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="edit_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Tipe Pembayaran</h3>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="pembayaran_id" id="edit_pembayaran_id">

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Tipe Pembayaran</span>
                    </label>
                    <input type="text" name="nama_pembayaran" id="edit_pembayaran_name" class="input input-bordered w-full" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="edit_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600 mb-4">Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus tipe pembayaran ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form method="POST" id="deleteForm" class="mt-6">
                @csrf
                @method('DELETE')

                <input type="hidden" name="pembayaran_id" id="delete_pembayaran_id">

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
            const name = button.dataset.nama;

            document.getElementById("edit_pembayaran_id").value = id;
            document.getElementById("edit_pembayaran_name").value = name;

            document.getElementById("editForm").action = "{{ url('admin/pembayarans') }}/" + id;

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;

            document.getElementById("delete_pembayaran_id").value = id;

            document.getElementById("deleteForm").action = "{{ url('admin/pembayarans') }}/" + id;

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
