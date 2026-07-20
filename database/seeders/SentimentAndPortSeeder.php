<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentimentAndPortSeeder extends Seeder
{
    public function run()
    {
        // Kunci ke sentiment_words sesuai isi file migration abang tadi
        $tableName = 'sentiment_words';

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 1. Suntik Kamus Kata Positif
        DB::table($tableName)->where('type', 'positive')->delete();
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'recovery', 'boom', 'safe', 'secure', 'efficient', 'surplus'];
        foreach ($positiveWords as $word) {
            DB::table($tableName)->updateOrInsert(['word' => $word], ['type' => 'positive']);
        }

        // 2. Suntik Kamus Kata Negatif
        DB::table($tableName)->where('type', 'negative')->delete();
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'strike', 'congested', 'conflict', 'sanction', 'blockade', 'decrease', 'deficit'];
        foreach ($negativeWords as $word) {
            DB::table($tableName)->updateOrInsert(['word' => $word], ['type' => 'negative']);
        }

        // 3. Suntik Sampel Data Pelabuhan Dunia
        DB::table('ports')->truncate();
        DB::table('ports')->insert([
            [
                'port_name' => 'Port of Tanjung Priok', 'country_code' => 'ID', 'country_name' => 'Indonesia',
                'latitude' => -6.1011, 'longitude' => 106.8831, 'risk_status' => 'Medium', 'risk_score' => 21.15,
                'details' => 'Normal customs operations with moderate maritime lane congestion.'
            ],
            [
                'port_name' => 'Port of Singapore', 'country_code' => 'SG', 'country_name' => 'Singapore',
                'latitude' => 1.2644, 'longitude' => 103.8384, 'risk_status' => 'Low', 'risk_score' => 2.45,
                'details' => 'High automation operational efficiency. Zero security backlogs reported.'
            ],
            [
                'port_name' => 'Port of Odesa', 'country_code' => 'UA', 'country_name' => 'Ukraine',
                'latitude' => 46.4857, 'longitude' => 30.7434, 'risk_status' => 'Critical', 'risk_score' => 74.40,
                'details' => 'ACTIVE WARZONE THREAT. Maritime supply chain completely disrupted.'
            ],
            [
                'port_name' => 'Port of Rotterdam', 'country_code' => 'NL', 'country_name' => 'Netherlands',
                'latitude' => 51.9244, 'longitude' => 4.4777, 'risk_status' => 'Low', 'risk_score' => 5.80,
                'details' => 'Smooth trade lane entry point to Western European manufacturing hubs.'
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}