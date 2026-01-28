<x-layouts.admin title="Manajemen Lokasi">

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

    <div class="container mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Lokasi</h1>
            <button class="btn btn-primary" onclick="openAddModal()">
                + Tambah Lokasi
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Lokasi</th>
                        <th class="w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lokasis as $index => $lokasi)
                        <tr class="hover">
                            <th>{{ $lokasis->firstItem() + $index }}</th>
                            <td class="font-medium">{{ $lokasi->nama }}</td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="btn btn-sm btn-warning btn-outline"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $lokasi->id }}"
                                            data-nama="{{ $lokasi->nama }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-error btn-outline"
                                            onclick="openDeleteModal(this)"
                                            data-id="{{ $lokasi->id }}"
                                            data-nama="{{ $lokasi->nama }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 mb-4" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 21s-6-5.686-6-10a6 6 0 1 1 12 0c0 4.314-6 10-6 10z" />
                                        <circle cx="12" cy="11" r="2.5" fill="currentColor"/>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-700">Belum Ada Lokasi</h3>
                                    <p class="text-gray-500 mt-2">Mulai dengan menambahkan lokasi baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $lokasis->links() }}
        </div>
    </div>

    <dialog id="add_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Lokasi Baru</h3>
            <form method="POST" action="{{ route('admin.lokasis.store') }}" id="addForm">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Lokasi</span>
                    </label>
                    <input type="text" name="nama" id="add_nama" placeholder="Contoh: Jakarta Pusat"
                           class="input input-bordered w-full @error('nama') input-error @enderror"
                           value="" required />
                    @error('nama')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <form method="dialog" class="modal-backdrop">
            <button onclick="closeAddModal()">close</button>
        </form>
    </dialog>

<dialog id="edit_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Edit Lokasi</h3>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Lokasi</span>
                </label>
                <input type="text" name="nama" id="edit_nama"
                       class="input input-bordered w-full"
                       required />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button onclick="closeEditModal()">close</button>
    </form>
</dialog>

    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600 mb-4">Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus lokasi ini? Tindakan ini tidak dapat dibatalkan.</p>

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
        let hasServerError = false;

        function openAddModal() {
            if (!hasServerError) {
                resetAddModal();
            }
            add_modal.showModal();
        }

        function closeAddModal() {
            if (!hasServerError) {
                resetAddModal();
            }
            add_modal.close();
            hasServerError = false;
        }

        function resetAddModal() {
            const inputField = document.getElementById('add_nama');
            if (inputField) {
                inputField.value = '';
            }

            const errorMessages = document.querySelectorAll('#addForm .text-red-500');
            errorMessages.forEach(msg => msg.remove());

            const errorInputs = document.querySelectorAll('#addForm .input-error');
            errorInputs.forEach(input => input.classList.remove('input-error'));

            const form = document.getElementById('addForm');
            if (form) {
                form.reset();
            }
        }

        function openEditModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.nama;

            const errorMessages = document.querySelectorAll('#editForm .text-red-500');
            errorMessages.forEach(msg => msg.remove());
            const errorInputs = document.querySelectorAll('#editForm .input-error');
            errorInputs.forEach(input => input.classList.remove('input-error'));

            document.getElementById("edit_nama").value = name;
            document.getElementById("editForm").action = "{{ url('admin/lokasis') }}/" + id;

            edit_modal.showModal();
        }

        function closeEditModal() {
            edit_modal.close();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.nama;

            document.getElementById("deleteForm").action = "{{ url('admin/lokasis') }}/" + id;

            delete_modal.showModal();
        }

        document.getElementById('add_modal').addEventListener('close', function() {
            setTimeout(function() {
                if (!hasServerError) {
                    resetAddModal();
                }
            }, 100);
        });

        document.getElementById('add_modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('edit_modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEditModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const hasAddError = document.querySelectorAll('#addForm .text-red-500').length > 0;

            if (hasAddError) {
                hasServerError = true;
                add_modal.showModal();
            } else {
                resetAddModal();
            }

            const hasEditError = document.querySelectorAll('#editForm .text-red-500').length > 0;
            if (hasEditError) {
                edit_modal.showModal();
            }
        });

        document.getElementById('addForm').addEventListener('submit', function() {
            hasServerError = false;
        });
    </script>
</x-layouts.admin>
