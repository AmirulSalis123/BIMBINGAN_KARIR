<x-layouts.app>
  <section class="max-w-7xl mx-auto py-12 px-6">
    <nav class="mb-6">
      <div class="breadcrumbs text-sm">
        <ul>
          <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
          <li><span class="text-gray-500">Event</span></li>
          <li class="font-bold">{{ $event->judul }}</li>
        </ul>
      </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-8">
        
        <div class="card bg-base-100 shadow-xl border border-gray-100 overflow-hidden">
          <figure class="max-h-[400px]">
            <img src="{{ $event->gambar ? asset('images/events/' . $event->gambar) : 'https://placehold.co/800x400?text=No+Image' }}" 
                 alt="{{ $event->judul }}" 
                 class="w-full h-full object-cover" />
          </figure>
          <div class="card-body">
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $event->judul }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-2">
              <div class="flex items-center gap-1">
                📅 {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
              </div>
              <div class="flex items-center gap-1">
                📍 {{ $event->lokasi }}
              </div>
            </div>

            <div class="mt-4 flex gap-2">
              <span class="badge badge-primary badge-outline">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
              <span class="badge badge-ghost">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
            </div>

            <div class="divider"></div>
            
            <h3 class="font-bold text-lg mb-2">Deskripsi</h3>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->deskripsi }}</p>
          </div>
        </div>

        <div class="card bg-base-100 shadow-xl border border-gray-100">
          <div class="card-body">
            <h3 class="text-xl font-bold mb-4">Pilih Tiket</h3>

            <div class="space-y-4">
              @forelse($event->tikets as $tiket)
              <div class="card card-side bg-base-200/50 border border-base-200 p-4 items-center flex-col sm:flex-row gap-4">
                
                <div class="flex-1 w-full">
                  <h4 class="font-bold text-lg text-blue-900">{{ $tiket->tipe }}</h4>
                  <p class="text-sm text-gray-500">{{ $tiket->keterangan ?? 'Tiket masuk event.' }}</p>
                  <p class="text-xs font-semibold mt-1 {{ $tiket->stok > 0 ? 'text-success' : 'text-error' }}">
                    Stok: <span id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span>
                  </p>
                </div>

                <div class="w-full sm:w-48 text-right flex flex-col items-end">
                  <div class="text-lg font-bold text-gray-900">
                    {{ $tiket->harga ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                  </div>

                  @if($tiket->stok > 0)
                    <div class="mt-3 flex items-center justify-end gap-2">
                      <button type="button" class="btn btn-sm btn-square btn-outline" data-action="dec" data-id="{{ $tiket->id }}">−</button>
                      <input id="qty-{{ $tiket->id }}" type="number" min="0" max="{{ $tiket->stok }}" value="0"
                        class="input input-sm input-bordered w-14 text-center p-0" data-id="{{ $tiket->id }}" readonly />
                      <button type="button" class="btn btn-sm btn-square btn-outline" data-action="inc" data-id="{{ $tiket->id }}">+</button>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                      Subtotal: <span id="subtotal-{{ $tiket->id }}" class="font-semibold">Rp 0</span>
                    </div>
                  @else
                    <button class="btn btn-sm btn-disabled mt-3">Habis</button>
                  @endif
                </div>
              </div>
              @empty
                <div class="alert alert-warning">
                  <span>Tiket belum tersedia untuk acara ini.</span>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <aside class="lg:col-span-1">
        <div class="card sticky top-24 bg-base-100 shadow-xl border border-gray-100">
          <div class="card-body p-6">
            <h4 class="font-bold text-lg mb-4">Ringkasan Pesanan</h4>

            <div id="selectedList" class="space-y-3 text-sm text-gray-600 mb-4 min-h-[50px]">
              <p class="italic text-gray-400">Belum ada tiket dipilih</p>
            </div>

            <div class="divider my-2"></div>

            <div class="flex justify-between text-sm text-gray-500 mb-1">
              <span>Total Item</span>
              <span id="summaryItems" class="font-semibold">0</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="font-bold text-gray-900">Total Harga</span>
              <span id="summaryTotal" class="font-bold text-xl text-blue-900">Rp 0</span>
            </div>

            @auth
              <button id="checkoutButton" class="btn btn-primary bg-blue-900 hover:bg-blue-800 text-white btn-block mt-6" onclick="openCheckout()" disabled>
                Checkout Sekarang
              </button>
            @else
              <a href="{{ route('login') }}" class="btn btn-outline btn-block mt-6">
                Login untuk Beli
              </a>
            @endauth
          </div>
        </div>
      </aside>
    </div>

    <dialog id="checkout_modal" class="modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Pembelian</h3>
        
        <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm mb-4">
          <div id="modalItems" class="space-y-2">
            <p class="text-gray-500">Memuat item...</p>
          </div>
          <div class="divider my-2"></div>
          <div class="flex justify-between items-center font-bold text-lg">
            <span>Total Bayar</span>
            <span id="modalTotal" class="text-blue-900">Rp 0</span>
          </div>
        </div>

        <p class="text-sm text-gray-500 mb-6">Pastikan pesanan Anda sudah benar sebelum melanjutkan.</p>

        <div class="modal-action">
          <form method="dialog">
            <button class="btn btn-ghost">Batal</button>
          </form>
          {{-- Tombol Konfirmasi Bayar --}}
          <button type="button" class="btn btn-primary bg-blue-900 text-white" id="confirmCheckout">
            Ya, Bayar
          </button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>

  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Helper: Format Rupiah
      const formatRupiah = (num) => 'Rp ' + Number(num).toLocaleString('id-ID');

      // Data Tiket dari PHP ke JS
      const tickets = {
        @foreach($event->tikets as $tiket)
          {{ $tiket->id }}: {
            id: {{ $tiket->id }},
            price: {{ $tiket->harga ?? 0 }},
            stock: {{ $tiket->stok }},
            tipe: "{{ e($tiket->tipe) }}"
          },
        @endforeach
      };

      // Elemen DOM
      const els = {
        summaryItems: document.getElementById('summaryItems'),
        summaryTotal: document.getElementById('summaryTotal'),
        selectedList: document.getElementById('selectedList'),
        checkoutBtn: document.getElementById('checkoutButton'),
        modal: document.getElementById('checkout_modal'),
        modalItems: document.getElementById('modalItems'),
        modalTotal: document.getElementById('modalTotal')
      };

      // Update UI (Ringkasan & Subtotal)
      function updateUI() {
        let totalQty = 0;
        let totalPrice = 0;
        let listHtml = '';

        Object.values(tickets).forEach(t => {
          const input = document.getElementById(`qty-${t.id}`);
          if (!input) return;

          const qty = parseInt(input.value) || 0;
          
          // Update Subtotal per tiket
          const subtotalEl = document.getElementById(`subtotal-${t.id}`);
          if(subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);

          if (qty > 0) {
            totalQty += qty;
            totalPrice += (qty * t.price);
            listHtml += `
              <div class="flex justify-between items-center">
                <span>${t.tipe} <span class="text-xs text-gray-500">x${qty}</span></span>
                <span class="font-medium">${formatRupiah(qty * t.price)}</span>
              </div>`;
          }
        });

        // Update Ringkasan Kanan
        els.summaryItems.textContent = totalQty;
        els.summaryTotal.textContent = formatRupiah(totalPrice);
        els.selectedList.innerHTML = listHtml || '<p class="italic text-gray-400">Belum ada tiket dipilih</p>';

        // Enable/Disable Checkout Button
        if(els.checkoutBtn) {
            els.checkoutBtn.disabled = totalQty === 0;
        }
      }

      // Event Listener Tombol +/-
      document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const action = e.currentTarget.dataset.action; // 'inc' atau 'dec'
          const id = e.currentTarget.dataset.id;
          const input = document.getElementById(`qty-${id}`);
          const ticket = tickets[id];

          let currentVal = parseInt(input.value) || 0;

          if (action === 'inc' && currentVal < ticket.stock) {
            input.value = currentVal + 1;
          } else if (action === 'dec' && currentVal > 0) {
            input.value = currentVal - 1;
          }

          updateUI();
        });
      });

      // Fungsi Buka Modal Checkout
      window.openCheckout = function() {
        let modalHtml = '';
        let modalTotal = 0;

        Object.values(tickets).forEach(t => {
          const qty = parseInt(document.getElementById(`qty-${t.id}`).value) || 0;
          if (qty > 0) {
            const sub = qty * t.price;
            modalTotal += sub;
            modalHtml += `
              <div class="flex justify-between">
                <span>${t.tipe} (x${qty})</span>
                <span>${formatRupiah(sub)}</span>
              </div>`;
          }
        });

        els.modalItems.innerHTML = modalHtml;
        els.modalTotal.textContent = formatRupiah(modalTotal);
        els.modal.showModal();
      };

      // ==========================================
      // BAGIAN BARU: LOGIKA KIRIM DATA KE SERVER
      // ==========================================
      const confirmBtn = document.getElementById('confirmCheckout');

      if (confirmBtn) {
          confirmBtn.addEventListener('click', async () => {
              // 1. Matikan tombol agar tidak double click
              confirmBtn.setAttribute('disabled', 'disabled');
              confirmBtn.textContent = 'Memproses...';

              // 2. Kumpulkan data item yang dipilih
              const items = [];
              Object.values(tickets).forEach(t => {
                  const qtyInput = document.getElementById('qty-' + t.id);
                  const qty = Number(qtyInput ? qtyInput.value : 0);
                  
                  if (qty > 0) {
                      items.push({ tiket_id: t.id, jumlah: qty });
                  }
              });

              // 3. Cek jika kosong
              if (items.length === 0) {
                  alert('Pilih minimal 1 tiket!');
                  confirmBtn.removeAttribute('disabled');
                  confirmBtn.textContent = 'Ya, Bayar';
                  return;
              }

              // 4. Kirim ke Server via Fetch API
              try {
                  const response = await fetch("{{ route('orders.store') }}", {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      body: JSON.stringify({ 
                          event_id: {{ $event->id }}, 
                          items: items 
                      })
                  });

                  const result = await response.json();

                  if (!response.ok) {
                      throw new Error(result.message || 'Gagal memproses pesanan');
                  }

                  // 5. Sukses! Redirect ke halaman riwayat
                  window.location.href = result.redirect;

              } catch (error) {
                  console.error(error);
                  alert('Terjadi kesalahan: ' + error.message);
                  
                  // Reset tombol jika gagal
                  confirmBtn.removeAttribute('disabled');
                  confirmBtn.textContent = 'Ya, Bayar';
              }
          });
      }
      
      // Inisialisasi awal
      updateUI();
    });
  </script>
</x-layouts.app>