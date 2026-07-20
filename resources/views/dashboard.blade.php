@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            🛡️ Risk Intelligence Command Center
        </h2>
        <p class="text-sm text-gray-400 mt-1">Overview of global logistics threats and operational security indices</p>
    </div>
</div>

<!-- Grid Kartu Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block">Global Countries</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-bold text-white">247</span>
            <span class="text-[10px] bg-emerald-950/40 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-900/40 font-medium">Mapped</span>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block">Monitored Ports</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-bold text-white">1,840</span>
            <span class="text-xs text-gray-400">Hubs</span>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block">System Incidents</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-bold text-rose-500 animate-pulse">14</span>
            <span class="text-[10px] bg-rose-950/40 text-rose-400 px-1.5 py-0.5 rounded border border-rose-900/40 font-medium">Active Alerts</span>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider block">Average Risk Index</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-3xl font-bold text-amber-400">24.50</span>
            <span class="text-[10px] bg-amber-950/40 text-amber-400 px-1.5 py-0.5 rounded border border-amber-900/40 font-medium">Medium</span>
        </div>
    </div>
</div>

<!-- Layout Dua Kolom Tengah -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Ringkasan Engine & Comparison Quick Check -->
    <div class="space-y-6 lg:col-span-1">
        <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">
                Comparison Engine
            </h3>
            <p class="text-xs text-gray-400 mb-4">Quickly compare metrics between key trading nodes.</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Country A</label>
                    <select class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none">
                        <option>🇮🇩 Indonesia</option>
                        <option>🇸🇬 Singapore</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Country B</label>
                    <select class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none">
                        <option>🇩🇿 Algeria</option>
                        <option>🇺🇦 Ukraine</option>
                    </select>
                </div>
                <a href="/compare" class="block text-center w-full mt-4 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-2 rounded-lg transition">
                    Run Analysis
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Berita & Sentimen Logistik Terkini -->
    <div class="lg:col-span-2 bg-[#111827] border border-gray-800/80 rounded-xl p-6">
        <div class="flex justify-between items-center border-b border-gray-800 pb-3 mb-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">
                Live Supply Chain Threat Feed
            </h3>
            <span class="flex items-center gap-1.5 text-[10px] text-emerald-400 font-semibold bg-emerald-950/30 px-2 py-0.5 rounded border border-emerald-900/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live Streaming
            </span>
        </div>
        
        <div class="space-y-4">
            <!-- Feed 1 -->
            <div class="p-3.5 bg-[#0B0F17] rounded-lg border border-gray-800/60 flex flex-col justify-between gap-2">
                <div class="flex justify-between items-start gap-4">
                    <h4 class="text-xs font-bold text-gray-200 hover:text-emerald-400 cursor-pointer">Suez Canal congestion increases container freight transit delays by 48 hours</h4>
                    <span class="text-[10px] bg-rose-950/40 text-rose-400 font-bold px-2 py-0.5 rounded whitespace-nowrap border border-rose-900/40">Negative</span>
                </div>
                <div class="flex justify-between items-center text-[10px] text-gray-500">
                    <span>Source: Maritime Intelligence</span>
                    <span>12 mins ago</span>
                </div>
            </div>
            <!-- Feed 2 -->
            <div class="p-3.5 bg-[#0B0F17] rounded-lg border border-gray-800/60 flex flex-col justify-between gap-2">
                <div class="flex justify-between items-start gap-4">
                    <h4 class="text-xs font-bold text-gray-200 hover:text-emerald-400 cursor-pointer">Port of Singapore rolls out automated AI berth planning infrastructure</h4>
                    <span class="text-[10px] bg-emerald-950/40 text-emerald-400 font-bold px-2 py-0.5 rounded whitespace-nowrap border border-emerald-900/40">Positive</span>
                </div>
                <div class="flex justify-between items-center text-[10px] text-gray-500">
                    <span>Source: TechLogistics Asia</span>
                    <span>1 hour ago</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection