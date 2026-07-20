@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="mb-8">
    <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
        ⚙️ System & Profile Configuration
    </h2>
    <p class="text-sm text-gray-400 mt-1">Manage global analytic engines, user preferences, and data sync credentials</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri & Tengah: Panel Pengaturan -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Card 1: Account Settings -->
        <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">
                User Profile Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 font-medium">Full Name</label>
                    <input type="text" value="dodo mas" class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 font-medium">Email Address</label>
                    <input type="email" value="dodomas@supplyguard.pro" class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                </div>
            </div>
        </div>

        <!-- Card 2: Risk Engine Parameters -->
        <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">
                Risk Engine Thresholds
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                        <span>Critical Level Threshold</span>
                        <span class="text-rose-400 font-bold">40.00+</span>
                    </div>
                    <input type="range" min="30" max="70" value="40" class="w-full accent-emerald-500 bg-gray-800 rounded-lg appearance-none h-2">
                </div>
                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                        <span>Sentiment Refresh Interval</span>
                        <span class="text-emerald-400 font-bold">Every 15 Minutes</span>
                    </div>
                    <select class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
                        <option>Every 15 Minutes</option>
                        <option>Every 1 Hour</option>
                        <option>Every 12 Hours</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Status Integrasi API -->
    <div class="space-y-6">
        <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">
                System Integration
            </h3>
            <div class="space-y-4">
                <!-- API 1 -->
                <div class="flex items-center justify-between p-3 bg-[#0B0F17] rounded-lg border border-gray-800/60">
                    <div>
                        <h4 class="text-xs font-bold text-gray-200">GNews API Sync</h4>
                        <span class="text-[10px] text-emerald-400 font-semibold">CONNECTED</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                </div>
                <!-- API 2 -->
                <div class="flex items-center justify-between p-3 bg-[#0B0F17] rounded-lg border border-gray-800/60">
                    <div>
                        <h4 class="text-xs font-bold text-gray-200">OpenWeather Pro</h4>
                        <span class="text-[10px] text-emerald-400 font-semibold">CONNECTED</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                </div>
            </div>
            <button class="w-full mt-6 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                Save System Changes
            </button>
        </div>
    </div>
</div>
@endsection