@extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('dashboard') ? 'dashboard' : 'welcome'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
        <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg">📰</span> News & Global Events Intelligence
    </h2>

    <!-- Grid Berita Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri & Tengah: Feed Berita Berjalan -->
        <div class="lg:col-span-2 space-y-4 h-[650px] overflow-y-auto pr-2">
            
            @if(isset($realtimeNews) && count($realtimeNews) > 0)
                @foreach($realtimeNews as $news)
                    <div class="p-4 bg-[#111827] border border-gray-800/80 rounded-xl hover:border-gray-700 transition flex flex-col justify-between gap-3 shadow-sm">
                        <div class="flex justify-between items-start gap-4">
                            <h4 class="text-sm font-bold text-gray-200 hover:text-emerald-400 cursor-pointer transition">
                                {{ $news['title'] ?? 'No Title Available' }}
                            </h4>
                            <span class="text-[10px] uppercase font-bold px-2.5 py-0.5 rounded border whitespace-nowrap {{ $news['badge'] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                                {{ $news['sentiment'] ?? 'Neutral' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500 border-t border-gray-800/40 pt-2">
                            <span class="flex items-center gap-1">🌐 Source: {{ $news['source'] ?? 'Unknown Source' }}</span>
                            <span>🕒 {{ $news['time'] ?? 'Just now' }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 text-center bg-[#111827] border border-gray-800 rounded-xl text-gray-400">
                    ⚠️ Belum ada data berita yang dikirim dari controller/route lek.
                </div>
            @endif

        </div>

        <!-- Kolom Kanan: Widget Statistik Sentimen -->
        <div class="space-y-6">
            <div class="bg-[#111827] border border-gray-800/80 rounded-xl p-6 shadow-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">
                    Global Node Sentiment (24h)
                </h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-emerald-400 font-semibold">Positive Sentiment</span>
                            <span class="text-gray-400">58%</span>
                        </div>
                        <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 58%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-amber-400 font-semibold">Neutral Sentiment</span>
                            <span class="text-gray-400">22%</span>
                        </div>
                        <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: 22%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-red-400 font-semibold">Negative Risk Sentiment</span>
                            <span class="text-gray-400">20%</span>
                        </div>
                        <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection