<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\LengthAwarePaginator;

class PortController extends Controller
{
    public function index(Request $request)
    {
        // 1. Nanti kalau data port kamu sudah dimasukkan ke Database (via Migration/Seeder), 
        // kamu tinggal pakai baris ini dan hapus array mock di bawahnya:
        // $ports = \DB::table('ports')->paginate(25);

        // 2. Simulasi Data 11.810 Port (Persis seperti tampilan kawanmu)
        $page = $request->get('page', 1);
        $perPage = 25;
        $totalItems = 11810; 

        $startNumber = ($page - 1) * $perPage + 1;
        $items = [];

        // Sample data disesuaikan dengan struktur Migration kamu
        $samplePorts = [
            ['port_name' => 'Zuun (Zuen)', 'country_name' => 'Belgium', 'lat' => 50.7833333, 'lng' => 4.2666667],
            ['port_name' => 'Zuzemberk', 'country_name' => 'Slovenia', 'lat' => 45.8333333, 'lng' => 14.9166667],
            ['port_name' => 'Zuzenhausen', 'country_name' => 'Germany', 'lat' => 49.2833333, 'lng' => 8.8166667],
            ['port_name' => 'Zvornik', 'country_name' => 'Bosnia and Herzegovina', 'lat' => 44.3833333, 'lng' => 19.1000000],
            ['port_name' => 'Zwaanshoek', 'country_name' => 'Netherlands', 'lat' => 52.3166667, 'lng' => 4.6166667],
            ['port_name' => 'Zwevegem', 'country_name' => 'Belgium', 'lat' => 50.8000000, 'lng' => 3.3333333],
            ['port_name' => 'Zwijnaarde', 'country_name' => 'Belgium', 'lat' => 51.0000000, 'lng' => 3.7166667],
            ['port_name' => 'Zwijndrecht', 'country_name' => 'Belgium', 'lat' => 51.2166667, 'lng' => 4.3333333],
            ['port_name' => 'Zwinderen', 'country_name' => 'Netherlands', 'lat' => 52.7333333, 'lng' => 6.6833333],
            ['port_name' => 'Zyyi', 'country_name' => 'Cyprus', 'lat' => 34.7333333, 'lng' => 33.3333333],
        ];

        for ($i = 0; $i < $perPage; $i++) {
            $sample = $samplePorts[$i % count($samplePorts)];
            $items[] = [
                'no' => $startNumber + $i,
                'port_name' => $sample['port_name'],
                'country_name' => $sample['country_name'],
                'latitude' => number_format($sample['lat'], 6, '.', ''),
                'longitude' => number_format($sample['lng'], 6, '.', ''),
            ];
        }

        $ports = new LengthAwarePaginator(
            $items, 
            $totalItems, 
            $perPage, 
            $page, 
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $htmlContent = "
        @extends(view()->exists('layouts.app') ? 'layouts.app' : (view()->exists('welcome') ? 'welcome' : 'dashboard'))
        @section('content')
        <div class='container mx-auto px-4 py-6 bg-[#0b0c10] min-h-screen text-gray-200'>
            <div class='bg-[#12141d] border border-gray-800/80 rounded-2xl p-6 shadow-2xl'>
                <div class='overflow-x-auto'>
                    <table class='w-full text-left text-sm text-gray-300'>
                        <thead class='text-xs uppercase text-gray-400 border-b border-gray-800'>
                            <tr>
                                <th class='py-4 px-4 font-bold'>No</th>
                                <th class='py-4 px-4 font-bold'>Port Name</th>
                                <th class='py-4 px-4 font-bold'>Country</th>
                                <th class='py-4 px-4 font-bold'>Latitude</th>
                                <th class='py-4 px-4 font-bold'>Longitude</th>
                            </tr>
                        </thead>
                        <tbody class='divide-y divide-gray-800/60'>
                            @foreach(\$ports as \$port)
                                <tr class='hover:bg-gray-800/40 transition'>
                                    <td class='py-3.5 px-4 font-semibold text-gray-400'>{{ \$port['no'] }}</td>
                                    <td class='py-3.5 px-4 font-bold text-white'>{{ \$port['port_name'] }}</td>
                                    <td class='py-3.5 px-4 text-gray-300'>{{ \$port['country_name'] }}</td>
                                    <td class='py-3.5 px-4 font-mono text-gray-300'>{{ \$port['latitude'] }}</td>
                                    <td class='py-3.5 px-4 font-mono text-gray-300'>{{ \$port['longitude'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bar -->
                <div class='flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-gray-800/80 text-xs text-gray-400'>
                    <div>
                        Showing <span class='font-bold text-white'>{{ \$ports->firstItem() }}</span> to <span class='font-bold text-white'>{{ \$ports->lastItem() }}</span> of <span class='font-bold text-white'>{{ \$ports->total() }}</span> results
                    </div>
                    <div>
                        {{ \$ports->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endsection";

        return response(Blade::render($htmlContent, ['ports' => $ports]));
    }
}