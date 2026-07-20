@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="mb-8">
    <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
        📊 Macroeconomic Risk Monitor
    </h2>
    <p class="text-sm text-gray-400 mt-1">Tracking fiscal stability, currency volatility, and GDP growth metrics across trade routes</p>
</div>

<!-- Grid Kartu Indikator Utama -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Highest Inflation Node</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-xl font-bold text-rose-400">Venezuela (140.2%)</span>
            <span class="text-[10px] bg-rose-950/50 text-rose-400 px-1.5 py-0.5 rounded border border-rose-900/40">Hyperinflation</span>
        </div>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-400 font-medium block text-emerald-400 flex items-center gap-1.5">
            Global Growth Leader
        </span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-xl font-bold text-white">India (+6.8%)</span>
            <span class="text-[10px] bg-emerald-950/50 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-900/30">Expansion</span>
        </div>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Fx Volatility Index</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-xl font-bold text-amber-400">High Risk (USD/TRY)</span>
            <span class="text-[10px] bg-amber-950/50 text-amber-400 px-1.5 py-0.5 rounded border border-amber-900/30">Unstable</span>
        </div>
    </div>
</div>

<!-- Tabel Data Ekonomi Global -->
<div class="bg-[#111827] border border-gray-800/80 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-800/60 flex justify-between items-center">
        <h3 class="text-base font-bold text-white">Key Trade Nodes Economic Ledger</h3>
        <span class="text-xs text-gray-500">Live fiscal indicators (Auto Generated Loop)</span>
    </div>
    <div class="overflow-x-auto h-[500px] overflow-y-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10">
                <tr class="text-xs text-gray-500 bg-[#111827] border-b border-gray-800">
                    <th class="p-4 font-semibold">Country</th>
                    <th class="p-4 font-semibold">GDP Growth</th>
                    <th class="p-4 font-semibold">Inflation Rate</th>
                    <th class="p-4 font-semibold">Local Currency</th>
                    <th class="p-4 font-semibold text-center">Debt-to-GDP Risk</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-800/40">
                @php
                    // Array dasar data ekonomi lek
                    $baseEco = [
                        ['c' => '🇮🇩 Indonesia', 'gdp' => '+5.05%', 'inf' => '2.8%', 'cur' => 'IDR (Stable)', 'risk' => 'Low Risk (39%)', 'col' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                        ['c' => '🇸🇬 Singapore', 'gdp' => '+2.10%', 'inf' => '1.9%', 'cur' => 'SGD (Strong)', 'risk' => 'Medium (160%)', 'col' => 'text-amber-400 bg-amber-950/50 border-amber-900/40'],
                        ['c' => '🇺🇸 United States', 'gdp' => '+2.40%', 'inf' => '3.1%', 'cur' => 'USD (Base)', 'risk' => 'High Alert (122%)', 'col' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                        ['c' => '🇩🇿 Algeria', 'gdp' => '+3.80%', 'inf' => '9.3%', 'cur' => 'DZD (Volatile)', 'risk' => 'Medium Risk (55%)', 'col' => 'text-amber-400 bg-amber-950/50 border-amber-900/40'],
                        ['c' => '🇯🇵 Japan', 'gdp' => '+0.90%', 'inf' => '2.5%', 'cur' => 'JPY (Safe Haven)', 'risk' => 'Critical Debt (260%)', 'col' => 'text-rose-400 bg-rose-950/50 border-rose-900/40'],
                        ['c' => '🇩🇪 Germany', 'gdp' => '-0.30%', 'inf' => '5.9%', 'cur' => 'EUR (Stable)', 'risk' => 'Low Risk (66%)', 'col' => 'text-emerald-400 bg-emerald-950/50 border-emerald-900/40'],
                    ];
                @endphp

                {{-- Kita loop sebanyak 30 baris contoh biar penuh layarnya lek --}}
                @for ($i = 0; $i < 255; $i++)
                    @php $data = $baseEco[$i % count($baseEco)]; @endphp
                    <tr class="hover:bg-gray-800/20 transition">
                        <td class="p-4 font-medium text-gray-200">{{ $data['c'] }} #{{ $i + 1 }}</td>
                        <td class="p-4 {{ str_contains($data['gdp'], '-') ? 'text-rose-400' : 'text-emerald-400' }} font-medium">{{ $data['gdp'] }}</td>
                        <td class="p-4 text-gray-300">{{ $data['inf'] }}</td>
                        <td class="p-4 text-gray-400">{{ $data['cur'] }}</td>
                        <td class="p-4 text-center">
                            <span class="text-[10px] border px-2 py-0.5 rounded font-semibold {{ $data['col'] }}">
                                {{ $data['risk'] }}
                            </span>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection