<x-layouts.admin title="Manajemen Kategori">
   
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
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Kategori</h1>
            <button class="btn btn-primary" onclick="add_modal.showModal()">
                + Tambah Kategori
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Kategori</th>
                        <th class="w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr class="hover">
                            <th>{{ $index + 1 }}</th>
                            <td class="font-medium">{{ $category->nama }}</td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="btn btn-sm btn-warning btn-outline" 
                                            onclick="openEditModal(this)" 
                                            data-id="{{ $category->id }}" 
                                            data-nama="{{ $category->nama }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-error btn-outline" 
                                            onclick="openDeleteModal(this)" 
                                            data-id="{{ $category->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="add_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Kategori Baru</h3>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Kategori</span>
                    </label>
                    <input type="text" name="nama" placeholder="Contoh: Musik Rock" class="input input-bordered w-full" required />
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
            <h3 class="font-bold text-lg mb-4">Edit Kategori</h3>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="category_id" id="edit_category_id">

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Kategori</span>
                    </label>
                    <input type="text" name="nama" id="edit_category_name" class="input input-bordered w-full" required />
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
            <p>Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <form method="POST" id="deleteForm" class="mt-6">
                @csrf
                @method('DELETE')
                
                <input type="hidden" name="category_id" id="delete_category_id">

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
            
            // Isi data ke dalam form modal
            document.getElementById("edit_category_id").value = id;
            document.getElementById("edit_category_name").value = name;

            // Update Action URL Form secara dinamis
            // Hati-hati: Pastikan URL prefix '/admin/categories/' sesuai route
            document.getElementById("editForm").action = "{{ url('admin/categories') }}/" + id;

            // Tampilkan Modal
            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            
            document.getElementById("delete_category_id").value = id;
            
            // Update Action URL Form
            document.getElementById("deleteForm").action = "{{ url('admin/categories') }}/" + id;

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>