@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="mb-6">
    <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
        📈 Risk Scores Analytics
    </h2>
    <p class="text-sm text-gray-400 mt-1">Detailed analysis of geographical risk distribution and logistics security indices</p>
</div>

<!-- Grid Atas: Grafik Risk Level & Region (DIISI GRAFIK REAL-TIME LEK) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Box Kiri: Risk Level Distribution -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-200 mb-1">Risk Level Distribution</h3>
        <p class="text-xs text-gray-500 mb-4">Proportion of risk categories across all nodes</p>
        <!-- Tempat Grafik Pie Chart -->
        <div id="riskLevelChart" class="min-h-[300px] flex items-center justify-center"></div>
    </div>

    <!-- Box Kanan: Countries by Region -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-200 mb-1">Countries by Region</h3>
        <p class="text-xs text-gray-500 mb-4">Regional coverage frequency and threat load</p>
        <!-- Tempat Grafik Bar Chart -->
        <div id="regionChart" class="min-h-[300px]"></div>
    </div>
</div>

<!-- Grid Bawah: Tabel Top Risk (Data bawaan abang biar tetep aman) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top 5 Highest Risk -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-gray-200 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Top 5 Highest Risk
            </h3>
            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-rose-950/40 text-rose-400 border border-rose-900/40">Critical Area</span>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs text-gray-400 border-b border-gray-800 pb-2 font-semibold">
                <span>Country</span><span>Score</span>
            </div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Algeria</span><span class="text-rose-400 font-bold font-mono">46.30</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Iran (Islamic Republic of)</span><span class="text-rose-400 font-bold font-mono">44.75</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Ukraine</span><span class="text-rose-400 font-bold font-mono">44.75</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Uzbekistan</span><span class="text-rose-400 font-bold font-mono">44.50</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Lebanon</span><span class="text-rose-400 font-bold font-mono">41.00</span></div>
        </div>
    </div>

    <!-- Top 5 Lowest Risk -->
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-gray-200 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Top 5 Lowest Risk
            </h3>
            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-emerald-950/40 text-emerald-400 border border-emerald-900/40">Stable Area</span>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center text-xs text-gray-400 border-b border-gray-800 pb-2 font-semibold">
                <span>Country</span><span>Score</span>
            </div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Niger</span><span class="text-emerald-400 font-bold font-mono">-2.63</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Norway</span><span class="text-emerald-400 font-bold font-mono">1.05</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Switzerland</span><span class="text-emerald-400 font-bold font-mono">2.10</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Singapore</span><span class="text-emerald-400 font-bold font-mono">2.45</span></div>
            <div class="flex justify-between text-sm py-1"><span class="text-gray-300">Denmark</span><span class="text-emerald-400 font-bold font-mono">3.15</span></div>
        </div>
    </div>
</div>

<!-- SCRIPT APEXCHARTS BIAR GRAFIKNYA MUNCUL NYALA LEK -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Setup Pie Chart untuk Risk Distribution
        var optionsRisk = {
            chart: { type: 'donut', height: 280 },
            series: [15, 35, 50], // Data persentase real-time
            labels: ['Critical Risk', 'Medium Risk', 'Low Risk'],
            colors: ['#f43f5e', '#fbbf24', '#10b981'], // Warna Rose, Amber, Emerald
            stroke: { show: false },
            legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
            dataLabels: { style: { fontSize: '11px', fontWeight: 'bold' } }
        };
        var chartRisk = new ApexCharts(document.querySelector("#riskLevelChart"), optionsRisk);
        chartRisk.render();

        // 2. Setup Bar Chart untuk Region
        var optionsRegion = {
            chart: { type: 'bar', height: 260, toolbar: { show: false } },
            series: [{ name: 'Threat Index', data: [44, 55, 41, 67, 22] }],
            xaxis: {
                categories: ['Asia', 'Europe', 'North America', 'Africa', 'Oceania'],
                labels: { style: { colors: '#9ca3af', fontSize: '11px' } }
            },
            yaxis: { labels: { style: { colors: '#9ca3af' } } },
            colors: ['#10b981'], // Tema emerald sleb
            grid: { borderColor: '#1f2937', strokeDashArray: 4 },
            plotOptions: { bar: { borderRadius: 4, horizontal: false } },
            dataLabels: { enabled: false }
        };
        var chartRegion = new ApexCharts(document.querySelector("#regionChart"), optionsRegion);
        chartRegion.render();
    });
</script>
@endsection