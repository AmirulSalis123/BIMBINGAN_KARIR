<x-layouts.admin title="Manajemen Tiket">

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

    @if (session('error'))
        <div class="toast toast-top toast-end z-50">
            <div class="alert alert-error text-white">
                <span>{{ session('error') }}</span>
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
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Tiket</h1>
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Tiket
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Tiket</th>
                        <th class="w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticketTypes as $index => $ticketType)
                        <tr class="hover">
                            <th>{{ $ticketTypes->firstItem() + $index }}</th>
                            <td class="font-medium">{{ $ticketType->name }}</td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="btn btn-sm btn-warning btn-outline"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $ticketType->id }}"
                                            data-name="{{ $ticketType->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-error btn-outline"
                                            onclick="openDeleteModal(this)"
                                            data-id="{{ $ticketType->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
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
                                            d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-700">Belum Ada Tiket</h3>
                                    <p class="text-gray-500 mt-2">Mulai dengan menambahkan tiket baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ticketTypes->hasPages())
            <div class="mt-6">
                {{ $ticketTypes->links() }}
            </div>
        @endif
    </div>

    <dialog id="add_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Tiket Baru</h3>
            <form method="POST" action="{{ route('admin.tikets.store') }}" id="addForm">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Tiket</span>
                    </label>
                    <input type="text" name="name" id="add_name" placeholder="Contoh: Tiket Reguler"
                           class="input input-bordered w-full @error('name') input-error @enderror"
                           value="" required />
                    @error('name')
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
        <h3 class="font-bold text-lg mb-4">Edit Tiket</h3>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="ticket_type_id" id="edit_ticket_type_id">

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Tiket</span>
                </label>
                <input type="text" name="name" id="edit_ticket_type_name"
                       class="input input-bordered w-full @error('name') input-error @enderror"
                       value="{{ $errors->has('name') && $errors->has('_method') ? old('name') : '' }}"
                       required />
                @error('name')
                    @if($errors->has('_method'))
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @endif
                @enderror
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
            <p>Apakah Anda yakin ingin menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form method="POST" id="deleteForm" class="mt-6">
                @csrf
                @method('DELETE')

                <input type="hidden" name="ticket_type_id" id="delete_ticket_type_id">

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
            const inputField = document.getElementById('add_name');
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
            const name = button.dataset.name;

            const errorMessages = document.querySelectorAll('#editForm .text-red-500');
            errorMessages.forEach(msg => msg.remove());
            const errorInputs = document.querySelectorAll('#editForm .input-error');
            errorInputs.forEach(input => input.classList.remove('input-error'));

            document.getElementById("edit_ticket_type_id").value = id;
            document.getElementById("edit_ticket_type_name").value = name;

            document.getElementById("editForm").action = "{{ url('admin/tikets') }}/" + id;

            edit_modal.showModal();
        }

        function closeEditModal() {
            edit_modal.close();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;

            document.getElementById("delete_ticket_type_id").value = id;

            document.getElementById("deleteForm").action = "{{ url('admin/tikets') }}/" + id;

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
