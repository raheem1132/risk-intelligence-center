@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            ⚓ Global Ports & Maritime Hubs
        </h2>
        <p class="text-sm text-gray-400 mt-1">Monitoring container congestion, anchorage waiting times, and operational status</p>
    </div>
    <div class="relative">
        <input type="text" placeholder="Search port or code..." class="bg-[#111827] border border-gray-800 rounded-lg px-4 py-2 text-xs text-white w-64 focus:outline-none focus:border-emerald-500 transition">
    </div>
</div>

<!-- Grid Kartu Summary Pelabuhan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Average Global Waiting Time</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-3xl font-bold text-white">24.2 <span class="text-xs text-gray-400 font-normal">Hours</span></span>
            <span class="text-[10px] bg-amber-950/50 text-amber-400 px-1.5 py-0.5 rounded border border-amber-900/30">Moderate Delay</span>
        </div>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-400 font-medium block text-rose-400 flex items-center gap-1.5">
            Most Congested Hub
        </span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-xl font-bold text-white">Port of Shanghai (CN)</span>
            <span class="text-[10px] bg-rose-950/50 text-rose-400 px-1.5 py-0.5 rounded border border-rose-900/40">Critical</span>
        </div>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Operational Efficiency</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-3xl font-bold text-emerald-400">94.8%</span>
            <span class="text-[10px] bg-emerald-950/50 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-900/30">Optimal</span>
        </div>
    </div>
</div>

<!-- Tabel Utama Ports -->
<div class="bg-[#111827] border border-gray-800/80 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-800/60 flex justify-between items-center">
        <h3 class="text-base font-bold text-white">Maritime Gateway Status Intelligence</h3>
        <span class="text-xs text-gray-500">Live congestion matrix (250+ Hubs Loop)</span>
    </div>
    <div class="overflow-x-auto h-[500px] overflow-y-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10">
                <tr class="text-xs text-gray-500 bg-[#111827] border-b border-gray-800">
                    <th class="p-4 font-semibold">Port Name</th>
                    <th class="p-4 font-semibold">Country</th>
                    <th class="p-4 font-semibold">UN/LOCODE</th>
                    <th class="p-4 font-semibold">Avg Waiting Time</th>
                    <th class="p-4 font-semibold text-center">Congestion Level</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-800/40">
                @php
                    // Array acuan data pelabuhan utama dunia lek
                    $basePorts = [
                        ['name' => 'Port of Singapore', 'country' => '🇸🇬 Singapore', 'code' => 'SGSIN', 'time' => '14.5 Hours', 'status' => 'Low Congestion', 'badge' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                        ['name' => 'Port of Shanghai', 'country' => '🇨🇳 China', 'code' => 'CNSHA', 'time' => '48.2 Hours', 'status' => 'Critical Threat', 'badge' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                        ['name' => 'Tanjung Priok', 'country' => '🇮🇩 Indonesia', 'code' => 'IDTPP', 'time' => '22.0 Hours', 'status' => 'Medium Alert', 'badge' => 'text-amber-400 bg-amber-950/50 border-amber-900/40'],
                        ['name' => 'Port of Rotterdam', 'country' => '🇳🇱 Netherlands', 'code' => 'NLRTM', 'time' => '18.1 Hours', 'status' => 'Low Congestion', 'badge' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                        ['name' => 'Port of Los Angeles', 'country' => '🇺🇸 United States', 'code' => 'USLAX', 'time' => '36.8 Hours', 'status' => 'High Alert', 'badge' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                    ];
                @endphp

                {{-- Kita loop sampai 250+ baris biar rimbun dan mantap lek --}}
                @for ($i = 0; $i < 255; $i++)
                    @php $port = $basePorts[$i % count($basePorts)]; @endphp
                    <tr class="hover:bg-gray-800/20 transition">
                        <td class="p-4 font-medium text-gray-200">
                            {{ $port['name'] }} @if($i >= count($basePorts)) Terminal {{ chr(65 + ($i % 4)) }} @endif
                        </td>
                        <td class="p-4 text-gray-400">{{ $port['country'] }}</td>
                        <td class="p-4 text-gray-500 font-mono text-xs">{{ $port['code'] }}-{{ 100 + $i }}</td>
                        <td class="p-4 text-gray-300 font-medium">{{ $port['time'] }}</td>
                        <td class="p-4 text-center">
                            <span class="text-[10px] border px-2 py-0.5 rounded font-semibold {{ $port['badge'] }}">
                                {{ $port['status'] }}
                            </span>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection