<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        // Kita pakai API berita gratisan dari NewsAPI / GNews. 
        // Sementara kita buat data generator yang super rimbun mencakup semua negara 
        // agar layoutnya tidak kosong sebelum abang memasukkan API Key aslinya.
        
        $countries = [
            ['flag' => '🇮🇩', 'name' => 'Indonesia'], ['flag' => '🇸🇬', 'name' => 'Singapore'],
            ['flag' => '🇲🇾', 'name' => 'Malaysia'], ['flag' => '🇹🇭', 'name' => 'Thailand'],
            ['flag' => '🇵🇭', 'name' => 'Philippines'], ['flag' => '🇻🇳', 'name' => 'Vietnam'],
            ['flag' => '🇯🇵', 'name' => 'Japan'], ['flag' => '🇰🇷', 'name' => 'South Korea'],
            ['flag' => '🇨🇳', 'name' => 'China'], ['flag' => '🇮🇳', 'name' => 'India'],
            ['flag' => '🇺🇸', 'name' => 'United States'], ['flag' => '🇬🇧', 'name' => 'United Kingdom'],
            ['flag' => '🇩🇪', 'name' => 'Germany'], ['flag' => '🇫🇷', 'name' => 'France'],
            ['flag' => '🇦🇺', 'name' => 'Australia'], ['flag' => '🇧🇷', 'name' => 'Brazil'],
            ['flag' => '🇿🇦', 'name' => 'South Africa'], ['flag' => '🇪🇬', 'name' => 'Egypt'],
            ['flag' => '🇩🇿', 'name' => 'Algeria'], ['flag' => '🇺🇦', 'name' => 'Ukraine']
        ];

        $topics = [
            'Suez Canal congestion increases container freight transit delays by 48 hours',
            'Customs checkpoint strike threatens cross-border automotive supply lines',
            'Port infrastructure expansion successfully slashes anchorage waiting times',
            'Geopolitical escalation forces major ocean carriers to reroute trade lanes',
            'New maritime trade regulations impact import tariffs and shipping schedules',
            'Severe weather warning disruptions reported near international cargo hubs'
        ];

        $sources = ['Maritime Intelligence', 'TechLogistics Asia', 'Global Risk Logs', 'EuroTransport News', 'Reuters Supply Chain'];

        // Generate otomatis 250+ berita real-time (1 berita per negara node)
        $realtimeNews = [];
        for ($i = 0; $i < 250; $i++) {
            $country = $countries[$i % count($countries)];
            $topic = $topics[$i % count($topics)];
            $source = $sources[($i + 3) % count($sources)];
            $sentiment = ($i % 3 == 0) ? 'Positive' : (($i % 3 == 1) ? 'Negative' : 'Neutral');

            $realtimeNews[] = [
                'title' => "[" . $country['flag'] . " " . $country['name'] . "] " . $topic . " (Node Update #" . ($i + 1) . ")",
                'source' => $source,
                'time' => rand(2, 59) . ' mins ago',
                'sentiment' => $sentiment,
                'badge' => $sentiment == 'Positive' ? 'bg-emerald-950/40 text-emerald-400 border-emerald-900/40' : ($sentiment == 'Negative' ? 'bg-rose-950/40 text-rose-400 border-rose-900/40' : 'bg-gray-800 text-gray-400 border-gray-700')
            ];
        }

        return view('news', compact('realtimeNews'));
    }
}