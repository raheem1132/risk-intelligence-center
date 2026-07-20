@extends('layouts.app')

@section('content')
<!-- Header Konten -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
            🌍 Global Jurisdictions (250+ Countries Mapped)
        </h2>
        <p class="text-sm text-gray-400 mt-1">Comprehensive list of tracked countries and regional data nodes</p>
    </div>
    <!-- Search Bar -->
    <div class="relative">
        <input type="text" placeholder="Search country..." class="bg-[#111827] border border-gray-800 rounded-lg px-4 py-2 text-xs text-white w-64 focus:outline-none focus:border-emerald-500 transition">
    </div>
</div>

<!-- Tabel Utama Countries -->
<div class="bg-[#111827] border border-gray-800/80 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-800/60 flex justify-between items-center">
        <h3 class="text-base font-bold text-white">Registered Countries Matrix</h3>
        <span class="text-xs text-gray-500">Showing 250+ entries via dynamic loop</span>
    </div>
    <div class="overflow-x-auto h-[600px] overflow-y-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10">
                <tr class="text-xs text-gray-500 bg-[#111827] border-b border-gray-800">
                    <th class="p-4 font-semibold w-20">Flag</th>
                    <th class="p-4 font-semibold">Country Name</th>
                    <th class="p-4 font-semibold">Region</th>
                    <th class="p-4 font-semibold">Subregion</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-800/40">
                @php
                    // Array Master Data Negara untuk di-looping sampai 250+ item dummy
                    $baseCountries = [
                        ['flag' => '🇮🇩', 'name' => 'Indonesia', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇸🇬', 'name' => 'Singapore', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇲🇾', 'name' => 'Malaysia', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇹🇭', 'name' => 'Thailand', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇵🇭', 'name' => 'Philippines', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇻🇳', 'name' => 'Vietnam', 'region' => 'Asia', 'sub' => 'South-Eastern Asia'],
                        ['flag' => '🇯🇵', 'name' => 'Japan', 'region' => 'Asia', 'sub' => 'Eastern Asia'],
                        ['flag' => '🇰🇷', 'name' => 'South Korea', 'region' => 'Asia', 'sub' => 'Eastern Asia'],
                        ['flag' => '🇨🇳', 'name' => 'China', 'region' => 'Asia', 'sub' => 'Eastern Asia'],
                        ['flag' => '🇮🇳', 'name' => 'India', 'region' => 'Asia', 'sub' => 'Southern Asia'],
                        ['flag' => '🇺🇸', 'name' => 'United States', 'region' => 'Americas', 'sub' => 'Northern America'],
                        ['flag' => '🇨🇦', 'name' => 'Canada', 'region' => 'Americas', 'sub' => 'Northern America'],
                        ['flag' => '🇬🇧', 'name' => 'United Kingdom', 'region' => 'Europe', 'sub' => 'Northern Europe'],
                        ['flag' => '🇩🇪', 'name' => 'Germany', 'region' => 'Europe', 'sub' => 'Western Europe'],
                        ['flag' => '🇫🇷', 'name' => 'France', 'region' => 'Europe', 'sub' => 'Western Europe'],
                        ['flag' => '🇳🇱', 'name' => 'Netherlands', 'region' => 'Europe', 'sub' => 'Western Europe'],
                        ['flag' => '🇦🇺', 'name' => 'Australia', 'region' => 'Oceania', 'sub' => 'Australia and New Zealand'],
                        ['flag' => '🇳🇿', 'name' => 'New Zealand', 'region' => 'Oceania', 'sub' => 'Australia and New Zealand'],
                        ['flag' => '🇧🇷', 'name' => 'Brazil', 'region' => 'Americas', 'sub' => 'South America'],
                        ['flag' => '🇿🇦', 'name' => 'South Africa', 'region' => 'Africa', 'sub' => 'Southern Africa'],
                        ['flag' => '🇪🇬', 'name' => 'Egypt', 'region' => 'Africa', 'sub' => 'Northern Africa'],
                        ['flag' => '🇩🇿', 'name' => 'Algeria', 'region' => 'Africa', 'sub' => 'Northern Africa'],
                        ['flag' => '🇺🇦', 'name' => 'Ukraine', 'region' => 'Europe', 'sub' => 'Eastern Europe'],
                        ['flag' => '🇸🇦', 'name' => 'Saudi Arabia', 'region' => 'Asia', 'sub' => 'Western Asia'],
                        ['flag' => '🇦🇪', 'name' => 'United Arab Emirates', 'region' => 'Asia', 'sub' => 'Western Asia'],
                    ];

                    // Kita generate otomatis sampai indeks 255 biar genap jadi list 250+ negara lek!
                    $fullCountries = [];
                    for ($i = 0; $i < 255; $i++) {
                        $base = $baseCountries[$i % count($baseCountries)];
                        // Biar nama negaranya unik, kita kasih nomor penanda dummy di belakangnya
                        $suffix = $i >= count($baseCountries) ? ' Node ' . ($i + 1) : '';
                        $fullCountries[] = [
                            'flag' => $base['flag'],
                            'name' => $base['name'] . $suffix,
                            'region' => $base['region'],
                            'sub' => $base['sub']
                        ];
                    }
                @endphp

                {{-- Looping cetak data 255 negara lek --}}
                @foreach($fullCountries as $country)
                <tr class="hover:bg-gray-800/20 transition">
                    <td class="p-4 text-xl">{{ $country['flag'] }}</td>
                    <td class="p-4 font-medium text-gray-200">{{ $country['name'] }}</td>
                    <td class="p-4 text-gray-400">| {{ $country['region'] }}</td>
                    <td class="p-4 text-gray-400">{{ $country['sub'] }}</td>
                    <td class="p-4 text-center">
                        <span class="text-[10px] bg-emerald-950/50 text-emerald-400 border border-emerald-900/40 px-2 py-0.5 rounded font-semibold">Active</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection