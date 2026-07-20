@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            📌 Watchlist Intelligence
        </h2>
        <p class="text-sm text-gray-400 mt-1">Monitor critical supply chain nodes and pinned jurisdictions</p>
    </div>
    <!-- Tombol Tambah Negara Interaktif -->
    <button onclick="toggleModal(true)" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(16,185,129,0.15)] flex items-center gap-1">
        + Add Country
    </button>
</div>

<!-- Grid Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Total Monitored</span>
        <span class="text-2xl font-bold text-white block mt-2" id="totalMonitored">4 <span class="text-xs text-gray-500 font-normal">Countries</span></span>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Critical Alerts
        </span>
        <span class="text-2xl font-bold text-white block mt-2">2 <span class="text-xs text-rose-400 font-semibold bg-rose-950/30 px-1.5 py-0.5 rounded border border-rose-900/40">High Risk</span></span>
    </div>
    <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-5">
        <span class="text-xs text-gray-500 font-medium block">Avg Risk Index</span>
        <span class="text-2xl font-bold text-white block mt-2">28.66 <span class="text-xs text-emerald-400 font-semibold bg-emerald-950/30 px-1.5 py-0.5 rounded border border-emerald-900/40 ml-1">Stable</span></span>
    </div>
</div>

<!-- Tabel Utama Pinned Jurisdictions -->
<div class="bg-[#111827] border border-gray-800/80 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-800/60 flex justify-between items-center">
        <h3 class="text-base font-bold text-white">Pinned Jurisdictions</h3>
        <span class="text-xs text-gray-500">Updated real-time</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="watchlistTable">
            <thead>
                <tr class="text-xs text-gray-500 border-b border-gray-800 bg-gray-950/20">
                    <th class="p-4 font-semibold">Country</th>
                    <th class="p-4 font-semibold">Region</th>
                    <th class="p-4 font-semibold">Risk Category</th>
                    <th class="p-4 font-semibold">Risk Score</th>
                    <th class="p-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-800/40 text-gray-300">
                <tr class="hover:bg-gray-800/20 transition">
                    <td class="p-4 font-semibold"><span class="text-gray-500 mr-1.5 font-mono text-xs">DZ</span> Algeria</td>
                    <td class="p-4 text-gray-400">Africa</td>
                    <td class="p-4"><span class="text-[10px] border px-2 py-0.5 rounded font-bold text-rose-400 bg-rose-950/40 border-rose-900/40">High Risk</span></td>
                    <td class="p-4 text-rose-400 font-bold font-mono">46.30</td>
                    <td class="p-4 text-right"><button onclick="removeRow(this)" class="text-xs text-gray-500 hover:text-rose-400 transition font-medium">Remove</button></td>
                </tr>
                <tr class="hover:bg-gray-800/20 transition">
                    <td class="p-4 font-semibold"><span class="text-gray-500 mr-1.5 font-mono text-xs">UA</span> Ukraine</td>
                    <td class="p-4 text-gray-400">Europe</td>
                    <td class="p-4"><span class="text-[10px] border px-2 py-0.5 rounded font-bold text-rose-400 bg-rose-950/40 border-rose-900/40">High Risk</span></td>
                    <td class="p-4 text-rose-400 font-bold font-mono">44.75</td>
                    <td class="p-4 text-right"><button onclick="removeRow(this)" class="text-xs text-gray-500 hover:text-rose-400 transition font-medium">Remove</button></td>
                </tr>
                <tr class="hover:bg-gray-800/20 transition">
                    <td class="p-4 font-semibold"><span class="text-gray-500 mr-1.5 font-mono text-xs">ID</span> Indonesia</td>
                    <td class="p-4 text-gray-400">Asia</td>
                    <td class="p-4"><span class="text-[10px] border px-2 py-0.5 rounded font-bold text-amber-400 bg-amber-950/40 border-amber-900/40">Medium Risk</span></td>
                    <td class="p-4 text-amber-400 font-bold font-mono">21.15</td>
                    <td class="p-4 text-right"><button onclick="removeRow(this)" class="text-xs text-gray-500 hover:text-rose-400 transition font-medium">Remove</button></td>
                </tr>
                <tr class="hover:bg-gray-800/20 transition">
                    <td class="p-4 font-semibold"><span class="text-gray-500 mr-1.5 font-mono text-xs">SG</span> Singapore</td>
                    <td class="p-4 text-gray-400">Asia</td>
                    <td class="p-4"><span class="text-[10px] border px-2 py-0.5 rounded font-bold text-emerald-400 bg-emerald-950/40 border-emerald-900/40">Low Risk</span></td>
                    <td class="p-4 text-emerald-400 font-bold font-mono">2.45</td>
                    <td class="p-4 text-right"><button onclick="removeRow(this)" class="text-xs text-gray-500 hover:text-rose-400 transition font-medium">Remove</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP MODAL UNTUK INPUT NEGARA BARU LEK -->
<div id="addModal" class="fixed inset-0 z-50 invisible flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm transition-all">
    <div class="bg-[#111827] border border-gray-800 w-full max-w-md rounded-xl p-6 shadow-2xl">
        <h3 class="text-base font-bold text-white mb-4">📍 Add Jurisdiction to Watchlist</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Country Name</label>
                <input type="text" id="newCountry" placeholder="e.g. Malaysia, Japan" class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Region</label>
                <select id="newRegion" class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500">
                    <option>Asia</option><option>Europe</option><option>Africa</option><option>North America</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Risk Score (0 - 100)</label>
                <input type="number" id="newScore" placeholder="e.g. 15.5" class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button onclick="toggleModal(false)" class="text-xs font-bold px-4 py-2 rounded-lg border border-gray-800 text-gray-400 hover:bg-gray-800 transition">Cancel</button>
            <button onclick="addCountryRow()" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition">Pin Country</button>
        </div>
    </div>
</div>

<!-- LOGIKA JAVASCRIPT BIAR JALAN PAS DIKLIK LEK -->
<script>
    function toggleModal(show) {
        const modal = document.getElementById('addModal');
        if(show) modal.classList.remove('invisible');
        else modal.classList.add('invisible');
    }

    function addCountryRow() {
        const name = document.getElementById('newCountry').value;
        const region = document.getElementById('newRegion').value;
        const score = parseFloat(document.getElementById('newScore').value) || 0;
        
        if(!name) return alert('Isi nama negaranya dulu lek!');

        let badge = 'text-emerald-400 bg-emerald-950/40 border-emerald-900/40';
        let category = 'Low Risk';
        if(score > 40) { badge = 'text-rose-400 bg-rose-950/40 border-rose-900/40'; category = 'High Risk'; }
        else if(score > 20) { badge = 'text-amber-400 bg-amber-950/40 border-amber-900/40'; category = 'Medium Risk'; }

        const code = name.substring(0, 2).toUpperCase();
        const tbody = document.querySelector('#watchlistTable tbody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-800/20 transition';
        row.innerHTML = `
            <td class="p-4 font-semibold"><span class="text-gray-500 mr-1.5 font-mono text-xs">${code}</span> ${name}</td>
            <td class="p-4 text-gray-400">${region}</td>
            <td class="p-4"><span class="text-[10px] border px-2 py-0.5 rounded font-bold ${badge}">${category}</span></td>
            <td class="p-4 ${score > 40 ? 'text-rose-400' : (score > 20 ? 'text-amber-400' : 'text-emerald-400')} font-bold font-mono">${score.toFixed(2)}</td>
            <td class="p-4 text-right"><button onclick="removeRow(this)" class="text-xs text-gray-500 hover:text-rose-400 transition font-medium">Remove</button></td>
        `;
        tbody.appendChild(row);
        updateTotal();
        toggleModal(false);
        document.getElementById('newCountry').value = '';
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        updateTotal();
    }

    function updateTotal() {
        const rows = document.querySelectorAll('#watchlistTable tbody tr').length;
        document.getElementById('totalMonitored').innerHTML = `${rows} <span class="text-xs text-gray-500 font-normal">Countries</span>`;
    }
</script>
@endsection