<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <aside class="w-full md:w-64 bg-white shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <img src="{{ asset('assets/images/logo_bengkod.svg') }}" alt="Logo" class="w-8 h-8">
                    BengTix
                </h2>

                <ul class="menu bg-base-100 w-full p-0 [&_li>*]:rounded-lg space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'active bg-blue-600 text-white' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('categories.index') }}"
                           class="{{ request()->routeIs('categories.*') ? 'active bg-blue-600 text-white' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            Kategori
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-4 border-t mt-auto">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-10">
                            <span class="text-xs">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="text-sm">
                        <p class="font-bold">{{ auth()->user()->name }}</p>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 text-xs hover:underline">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b p-4 flex justify-between items-center md:hidden">
                <span class="font-bold text-lg">Admin Panel</span>
                <button class="btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @if(session('success'))
                    <div role="alert" class="alert alert-success mb-4" id="alert-box">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <script>
                        setTimeout(() => { document.getElementById('alert-box').style.display = 'none'; }, 3000);
                    </script>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>