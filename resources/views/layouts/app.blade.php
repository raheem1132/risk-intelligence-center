<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0B0F17]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupplyGuard Pro - Risk Intelligence</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex text-gray-300 antialiased">

    <!-- SIDEBAR UTAMA (KIRI) -->
    <aside class="w-64 bg-[#111827] border-r border-gray-800/80 flex flex-col justify-between h-screen sticky top-0">
        <div class="p-6">
            <!-- Logo / Brand -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-8 h-8 rounded-lg bg-emerald-950/50 border border-emerald-900/50 flex items-center justify-center text-emerald-400">
                    🛡️
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-wide">Global Supply Chain</h1>
                    <span class="text-[10px] text-emerald-400 uppercase font-semibold tracking-wider block mt-0.5">Risk Intelligence</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider block px-3 mb-2">Main Control</span>
                
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    🎛️ Dashboard
                </a>
                <a href="/countries" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    🌍 Countries
                </a>
                <a href="/weather" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    ☁️ Weather
                </a>
                <a href="/economy" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    📊 Economy
                </a>
                <a href="/ports" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    ⚓ Ports
                </a>
                <a href="/news" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    📰 News & Events
                </a>

                <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider block px-3 pt-4 mb-2">Analytics</span>
                
                <a href="/risk-scores" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    📈 Risk Scores
                </a>
                <a href="/watchlist" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    🗂️ Watchlist
                </a>
                <a href="/compare" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    🔄 Compare
                </a>
                <a href="/map" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    🗺️ Global Map
                </a>
                <a href="/settings" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/50 transition">
                    ⚙️ Settings
                </a>
            </nav>
        </div>

        <!-- Bagian User Menu (Hasil Langkah 1 yang sudah Dinamis Lek) -->
        <div class="p-4 border-t border-gray-800/60 flex items-center gap-3 bg-gray-950/20">
            <div class="w-9 h-9 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xs font-bold text-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.1)]">
                USR
            </div>
            <div class="flex-1 min-w-0">
                <!-- Data ini otomatis mengambil nama user lek -->
                <h4 class="text-xs font-bold text-white truncate">{{ session('user_name', 'Dodo Mas') }}</h4>
                <span class="text-[10px] text-gray-500 block truncate">Active Station</span>
            </div>
            <a href="/" class="text-xs text-gray-500 hover:text-rose-400 transition" title="Disconnect Session">
                🚪
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA (KANAN) -->
    <main class="flex-1 p-8 h-screen overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>