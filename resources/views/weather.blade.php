@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            ☁️ Extreme Weather & Climate Risks
        </h2>
        <p class="text-sm text-gray-400 mt-1">Real-time tracking of meteorological anomalies disrupting global maritime trade lanes</p>
    </div>
    <span class="text-xs bg-amber-950/40 text-amber-400 px-3 py-1.5 rounded border border-amber-900/40 font-semibold flex items-center gap-1.5">
        ⚠️ 3 Active Typhoon Alerts
    </span>
</div>

<!-- Grid Summary Kondisi Cuaca Global -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">South China Sea Status</span>
        <span class="text-xl font-bold text-rose-400 block mt-2">Severe Typhoon</span>
        <span class="text-[10px] text-gray-500 block mt-1">Wind speed up to 140 km/h</span>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">North Atlantic Route</span>
        <span class="text-xl font-bold text-amber-400 block mt-2">Heavy Swells</span>
        <span class="text-[10px] text-gray-500 block mt-1">Wave heights: 6.5 meters</span>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Indian Ocean Node</span>
        <span class="text-xl font-bold text-emerald-400 block mt-2">Calm & Clear</span>
        <span class="text-[10px] text-gray-500 block mt-1">Optimal transit efficiency</span>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Panama Canal Basin</span>
        <span class="text-xl font-bold text-amber-400 block mt-2">Low Rainfall Risk</span>
        <span class="text-[10px] text-gray-500 block mt-1">Draft levels restricted to 44ft</span>
    </div>
</div>

<!-- Tabel Utama Weather Risk Metrics -->
<div class="bg-[#111827] border border-gray-800/80 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-800/60 flex justify-between items-center">
        <h3 class="text-base font-bold text-white">Global Maritime Grid Weather Matrix</h3>
        <span class="text-xs text-gray-500">250+ Global Supply Nodes Covered</span>
    </div>
    <div class="overflow-x-auto h-[450px] overflow-y-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10 bg-[#111827]">
                <tr class="text-xs text-gray-500 border-b border-gray-800">
                    <th class="p-4 font-semibold">Trade Route / Region</th>
                    <th class="p-4 font-semibold">Primary Port Node</th>
                    <th class="p-4 font-semibold">Visibility Index</th>
                    <th class="p-4 font-semibold">Wave Height</th>
                    <th class="p-4 font-semibold text-center">Supply Chain Threat Level</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-800/40">
                @php
                    $regions = [
                        ['route' => 'Malacca Strait Grid', 'port' => 'Port of Singapore (SG)', 'vis' => '8.5 km', 'wave' => '1.2m', 'threat' => 'Low Risk', 'badge' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                        ['route' => 'East China Sea Corridors', 'port' => 'Port of Shanghai (CN)', 'vis' => '2.1 km', 'wave' => '5.8m', 'threat' => 'Critical Threat', 'badge' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                        ['route' => 'Java Sea Network', 'port' => 'Tanjung Priok (ID)', 'vis' => '6.0 km', 'wave' => '2.0m', 'threat' => 'Medium Alert', 'badge' => 'text-amber-400 bg-amber-950/50 border-amber-900/40'],
                        ['route' => 'North Sea Lines', 'port' => 'Port of Rotterdam (NL)', 'vis' => '4.2 km', 'wave' => '4.1m', 'threat' => 'High Alert', 'badge' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                        ['route' => 'Transpacific Node A', 'port' => 'Port of Los Angeles (US)', 'vis' => '9.0 km', 'wave' => '1.5m', 'threat' => 'Low Risk', 'badge' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                    ];
                @endphp

                {{-- Loop rimbun sampai 250 baris sesuai permintaan lek muler --}}
                @for ($i = 0; $i < 250; $i++)
                    @php $data = $regions[$i % count($regions)]; @endphp
                    <tr class="hover:bg-gray-800/20 transition">
                        <td class="p-4 font-medium text-gray-200">
                            {{ $data['route'] }} #{{ 1000 + $i }}
                        </td>
                        <td class="p-4 text-gray-400">{{ $data['port'] }}</td>
                        <td class="p-4 text-gray-500 font-mono text-xs">{{ $data['vis'] }}</td>
                        <td class="p-4 text-gray-300">{{ $data['wave'] }}</td>
                        <td class="p-4 text-center">
                            <span class="text-[10px] border px-2 py-0.5 rounded font-semibold {{ $data['badge'] }}">
                                {{ $data['threat'] }}
                            </span>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection