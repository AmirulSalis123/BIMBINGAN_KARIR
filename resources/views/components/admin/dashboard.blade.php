<x-layouts.admin title="Dashboard Admin">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card bg-white shadow-xl border-l-4 border-blue-500">
                <div class="card-body">
                    <h2 class="card-title text-gray-500 text-sm uppercase">Total Event</h2>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalEvents ?? 0 }}</p>
                    <div class="card-actions justify-end">
                        <div class="badge badge-primary badge-outline">Active</div>
                    </div>
                </div>
            </div>

            <div class="card bg-white shadow-xl border-l-4 border-green-500">
                <div class="card-body">
                    <h2 class="card-title text-gray-500 text-sm uppercase">Total Kategori</h2>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalCategories ?? 0 }}</p>
                    <div class="card-actions justify-end">
                        <div class="badge badge-accent badge-outline">Types</div>
                    </div>
                </div>
            </div>

            <div class="card bg-white shadow-xl border-l-4 border-purple-500">
                <div class="card-body">
                    <h2 class="card-title text-gray-500 text-sm uppercase">Total Transaksi</h2>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalOrders ?? 0 }}</p>
                    <div class="card-actions justify-end">
                        <div class="badge badge-secondary badge-outline">Sales</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>