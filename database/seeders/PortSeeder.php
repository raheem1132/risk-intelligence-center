<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel lama lek biar gak tumpat
        DB::table('ports')->truncate();

        // 1. FONDASI UTAMA: Hub Logistik Terbesar di Dunia (Real Life Data)
        $hubs = [
            // Indonesia
            ['Tanjung Priok', 'ID', 'Indonesia', -6.1014, 106.8831],
            ['Tanjung Perak', 'ID', 'Indonesia', -7.2024, 112.7247],
            ['Belawan', 'ID', 'Indonesia', 3.7849, 98.6923],
            ['Makassar Port', 'ID', 'Indonesia', -5.1186, 119.4042],
            ['Tanjung Emas', 'ID', 'Indonesia', -6.9536, 110.4203],
            // Asia Tenggara & Timur
            ['Singapore Sector A', 'SG', 'Singapore', 1.2740, 103.8443],
            ['Singapore Sector B', 'SG', 'Singapore', 1.2650, 103.8250],
            ['Port Klang Main', 'MY', 'Malaysia', 2.9996, 101.3923],
            ['Tanjung Pelepas', 'MY', 'Malaysia', 1.3653, 103.5452],
            ['Laem Chabang Terminal', 'TH', 'Thailand', 13.0906, 100.8994],
            ['Manila South Harbor', 'PH', 'Philippines', 14.6111, 120.9617],
            ['Saigon Port Complex', 'VN', 'Vietnam', 10.7681, 106.7411],
            ['Shanghai Deepwater', 'CN', 'China', 31.2222, 121.4581],
            ['Shenzhen Container Port', 'CN', 'China', 22.5044, 113.8828],
            ['Ningbo-Zhoushan Hub', 'CN', 'China', 29.8683, 121.5440],
            ['Hong Kong Kwai Tsing', 'HK', 'Hong Kong', 22.3324, 114.1242],
            ['Tokyo Maritime Bay', 'JP', 'Japan', 35.6262, 139.7733],
            ['Yokohama Pier', 'JP', 'Japan', 35.4522, 139.6542],
            ['Busan North Port', 'KR', 'South Korea', 35.1041, 129.0416],
            // Timur Tengah & India
            ['Jebel Ali Freezone', 'AE', 'United Arab Emirates', 25.0112, 55.0614],
            ['Jeddah Islamic Port', 'SA', 'Saudi Arabia', 21.4858, 39.1875],
            ['Nhava Sheva JNPT', 'IN', 'India', 18.9482, 72.9486],
            ['Colombo International', 'LK', 'Sri Lanka', 6.9424, 79.8428],
            // Eropa
            ['Rotterdam Maasvlakte', 'NL', 'Netherlands', 51.9489, 4.1425],
            ['Antwerp Euroterminal', 'BE', 'Belgium', 51.2411, 4.4014],
            ['Hamburg Burchardkai', 'DE', 'Germany', 53.5324, 9.9642],
            ['Felixstowe Trinity', 'GB', 'United Kingdom', 51.9642, 1.3114],
            ['Valencia Principe Felipe', 'ES', 'Spain', 39.4489, -0.3247],
            ['Piraeus Container Terminal', 'GR', 'Greece', 37.9424, 23.6331],
            // Amerika
            ['Los Angeles Pier 400', 'US', 'United States', 33.7422, -118.2636],
            ['Long Beach Pier T', 'US', 'United States', 33.7542, -118.2111],
            ['New York Elizabeth Marine', 'US', 'United States', 40.6714, -74.1206],
            ['Houston Barbour Cut', 'US', 'United States', 29.7422, -95.2642],
            ['Santos Saboo Terminal', 'BR', 'Brazil', -23.9614, -46.3322],
            // Afrika & Australia
            ['Durban Pier 1', 'ZA', 'South Africa', -29.8714, 31.0247],
            ['Alexandria El-Dekheila', 'EG', 'Egypt', 31.2014, 29.8828],
            ['Melbourne Swanson Dock', 'AU', 'Australia', -37.8311, 144.9124],
            ['Sydney Botany Bay', 'AU', 'Australia', -33.8614, 151.2111],
        ];

        $portsData = [];
        $now = now();

        // Masukkan data dasar utama
        foreach ($hubs as $h) {
            $risk = rand(12, 85);
            $status = $risk >= 70 ? 'High' : ($risk >= 40 ? 'Medium' : 'Low');
            $portsData[] = [
                'port_name' => $h[0],
                'country_code' => $h[1],
                'country_name' => $h[2],
                'latitude' => $h[3],
                'longitude' => $h[4],
                'risk_score' => $risk,
                'risk_status' => $status,
                'details' => 'Primary container gateway terminal node.',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 2. VARIASI FORMAT PENAMAAN DUNIA NYATA UNTUK GENERATOR (Biar totalnya pas 500)
        $locations = [
            ['ID', 'Indonesia', -5.0, 115.0, ['Merak', 'Semarang', 'Surabaya', 'Cirebon', 'Batam', 'Cilegon', 'Dumai', 'Medan']],
            ['SG', 'Singapore', 1.2, 103.8, ['Pasir Panjang', 'Tuas Mega', 'Keppel', 'Brani', 'Sembawang', 'Jurong']],
            ['MY', 'Malaysia', 3.0, 101.5, ['Penang', 'Kuantan', 'Bintulu', 'Labuan', 'Johor', 'Kemaman']],
            ['CN', 'China', 30.0, 118.0, ['Guangzhou', 'Qingdao', 'Tianjin', 'Dalian', 'Xiamen', 'Yantian', 'Fuzhou']],
            ['US', 'United States', 36.0, -115.0, ['Savannah', 'Seattle', 'Oakland', 'Miami', 'Tacoma', 'Charleston', 'Norfolk']],
            ['NL', 'Netherlands', 52.0, 4.5, ['Amsterdam', 'Zeebrugge', 'Vlissingen', 'Dordrecht']],
            ['DE', 'Germany', 53.5, 9.5, ['Bremen', 'Bremerhaven', 'Rostock', 'Kiel', 'Wilhelmshaven']],
            ['JP', 'Japan', 35.0, 137.0, ['Kobe', 'Nagoya', 'Osaka', 'Hakata', 'Chiba', 'Kitakyushu']],
            ['KR', 'South Korea', 36.0, 127.0, ['Incheon', 'Gwangyang', 'Ulsan', 'Pyeongtaek', 'Mokpo']],
            ['IN', 'India', 19.0, 73.0, ['Mundra', 'Chennai', 'Kolkata', 'Kandla', 'Cochin', 'Visakhapatnam']],
            ['AU', 'Australia', -25.0, 135.0, ['Brisbane', 'Fremantle', 'Adelaide', 'Darwin', 'Newcastle', 'Hedland']],
            ['BR', 'Brazil', -20.0, -45.0, ['Paranagua', 'Rio de Janeiro', 'Vitoria', 'Itajai', 'Manaus']],
            ['ZA', 'South Africa', -30.0, 25.0, ['Cape Town', 'Port Elizabeth', 'Saldanha', 'Richards Bay', 'East London']],
            ['EG', 'Egypt', 30.0, 31.2, ['Port Said East', 'Port Said West', 'Damietta', 'Suez Canopy', 'Safaga']]
        ];

        $terminalTypes = ['Container Terminal', 'Bulk Gateway', 'International Pier', 'Logistics Zone', 'Cargo Wharf', 'General Berth'];

        $currentCount = count($portsData);
        $targetCount = 500;

        while ($currentCount < $targetCount) {
            // Pilih negara acak dari list variasi
            $loc = $locations[array_rand($locations)];
            $baseName = $loc[4][array_rand($loc[4])];
            $type = $terminalTypes[array_rand($terminalTypes)];
            $num = rand(1, 9);
            
            // Susun nama berstandar pelabuhan internasional komersial
            $fullName = "{$baseName} {$type} {$num}";

            // Cek duplikasi nama biar ga ada yang kembar lek
            $exists = false;
            foreach ($portsData as $p) {
                if ($p['port_name'] === $fullName) {
                    $exists = true;
                    break;
                }
            }
            if ($exists) continue;

            $risk = rand(10, 90);
            $status = $risk >= 70 ? 'High' : ($risk >= 40 ? 'Medium' : 'Low');

            // Beri sedikit variasi koordinat di sekitar wilayah regional negara tersebut
            $lat = $loc[2] + (rand(-300, 300) / 100);
            $lon = $loc[3] + (rand(-300, 300) / 100);

            $portsData[] = [
                'port_name' => $fullName,
                'country_code' => $loc[0],
                'country_name' => $loc[1],
                'latitude' => $lat,
                'longitude' => $lon,
                'risk_score' => $risk,
                'risk_status' => $status,
                'details' => "Commercial marine infrastructure hub at {$baseName}.",
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $currentCount++;
        }

        // Massal push ke database per 100 data biar kencang
        foreach (array_chunk($portsData, 100) as $chunk) {
            DB::table('ports')->insert($chunk);
        }
    }
}