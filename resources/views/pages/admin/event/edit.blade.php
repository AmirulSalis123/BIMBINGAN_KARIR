<x-layouts.admin title="Edit Event">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-4 mb-3">
                    <button type="button"
                            onclick="window.location.href='{{ route('admin.events.index') }}'"
                            class="btn btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>

                    <!-- Judul -->
                    <h2 class="card-title text-2xl">Edit Event</h2>
                </div>

                <form id="eventForm" class="space-y-4" method="post"
                    action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Judul Event</span>
                        </label>
                        <input type="text" name="judul" placeholder="Contoh: Konser Musik Rock"
                            class="input input-bordered w-full @error('judul') input-error @enderror"
                            value="{{ old('judul', $event->judul) }}" required />
                        @error('judul')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea name="deskripsi" placeholder="Deskripsi lengkap tentang event..."
                            class="textarea textarea-bordered h-24 w-full"
                            required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu</span>
                        </label>
                        <input type="datetime-local" name="tanggal_waktu"
                            class="input input-bordered w-full"
                            value="{{ old('tanggal_waktu', $event->tanggal_waktu->format('Y-m-d\TH:i')) }}"
                            required />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Lokasi</span>
                        </label>
                        <select name="lokasi_id"
                            class="select select-bordered w-full"
                            required>
                            <option value="" disabled>Pilih Lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('lokasi_id', $event->lokasi_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="kategori_id"
                            class="select select-bordered w-full"
                            required>
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('kategori_id', $event->kategori_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Event</span>
                        </label>
                        <input type="file" name="gambar" accept="image/*"
                            class="file-input file-input-bordered w-full" />
                        <label class="label">
                            <span class="label-text-alt">Format: JPG, PNG, max 2MB</span>
                        </label>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="overflow-hidden {{ $event->gambar ? '' : 'hidden' }}">
                        <label class="label">
                            <span class="label-text font-semibold">Preview Gambar</span>
                        </label>
                        <br>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                @if ($event->gambar)
                                    <img id="previewImg" src="{{ asset('images/events/' . $event->gambar) }}"
                                        alt="Preview">
                                @else
                                    <img id="previewImg" src="" alt="Preview">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="card-actions justify-end mt-6">
                        <button type="button" id="resetBtn"
                                class="btn btn-ghost">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('eventForm');
        const fileInput = form.querySelector('input[type="file"]');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const resetBtn = document.getElementById('resetBtn');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        let isSubmitting = false;

        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Menyimpan...';

            return true;
        });

        resetBtn.addEventListener('click', function() {
            const textInputs = form.querySelectorAll('input[type="text"], input[type="datetime-local"], textarea');
            textInputs.forEach(input => {
                input.value = '';
            });

            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });

            fileInput.value = '';

            imagePreview.classList.add('hidden');
            previewImg.src = '';

            const errorInputs = form.querySelectorAll('.input-error');
            errorInputs.forEach(input => input.classList.remove('input-error'));

            const errorMessages = form.querySelectorAll('.text-red-500');
            errorMessages.forEach(msg => msg.remove());

            if (textInputs.length > 0) {
                textInputs[0].focus();
            }

            alert('Form telah direset. Semua data telah dihapus.');
        });
    </script>
</x-layouts.admin>
