<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SentimentDictionary;
use App\Models\NewsCache;
use Carbon\Carbon;

class NewsService
{
    /**
     * Mengambil berita dari GNews API dan menghitung skor sentimennya.
     */
    public function fetchAndAnalyzeNews($countryCode, $keyword = 'economy')
    {
        // 1. Cek dulu apakah ada cache berita untuk negara & keyword ini yang masih segar (< 3 jam)
        $cached = NewsCache::where('country_code', $countryCode)
                            ->where('keyword', $keyword)
                            ->where('updated_at', '>=', Carbon::now()->subHours(3))
                            ->first();

        if ($cached) {
            return json_decode($cached->payload, true);
        }

        // 2. Jika tidak ada cache, tembak GNews API
        // Catatan: GNews menggunakan parameter 'q' untuk query pencarian berita
        $apiKey = config('services.gnews.key'); // Pastikan nanti disetting di .env
        $query = "{$keyword} AND \"{$countryCode}\""; 
        
        $response = Http::get("https://gnews.io/api/v4/search", [
            'q' => $query,
            'lang' => 'en',
            'max' => 5, // Batasi 5 berita saja demi efisiensi
            'apikey' => $apiKey
        ]);

        if ($response->failed()) {
            return ['status' => 'error', 'message' => 'Gagal mengambil data dari GNews API'];
        }

        $articles = $response->json()['articles'] ?? [];
        $analyzedArticles = [];

        // Ambil data kamus kata dari database kita kemarin
        $positiveWords = SentimentDictionary::where('type', 'positive')->pluck('word')->toArray();
        $negativeWords = SentimentDictionary::where('type', 'negative')->pluck('word')->toArray();

        foreach ($articles as $article) {
            $textToAnalyze = strtolower($article['title'] . ' ' . $article['description']);
            
            // Bersihkan teks dari tanda baca untuk pemecahan kata baku
            $cleanText = preg_replace('/[^\w\s]/', '', $textToAnalyze);
            $words = explode(' ', $cleanText);

            $posCount = 0;
            $negCount = 0;

            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) {
                    $posCount++;
                }
                if (in_array($word, $negativeWords)) {
                    $negCount++;
                }
            }

            $totalScore = $posCount + $negCount;
            
            // Hitung rasio persentase sentimen sesuai spesifikasi tugas
            if ($totalScore > 0) {
                $posPercent = round(($posCount / $totalScore) * 100);
                $negPercent = round(($negCount / $totalScore) * 100);
                $neuPercent = 0;
            } else {
                // Jika tidak ada kata yang cocok, otomatis dianggap Netral 100%
                $posPercent = 0;
                $negPercent = 0;
                $neuPercent = 100;
            }

            // Tentukan label sentimen akhir artikel
            if ($posPercent > $negPercent) {
                $sentimentLabel = 'Positive';
            } elseif ($negPercent > $posPercent) {
                $sentimentLabel = 'Negative';
            } else {
                $sentimentLabel = 'Neutral';
            }

            $analyzedArticles[] = [
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'image' => $article['image'] ?? null,
                'published_at' => $article['publishedAt'],
                'sentiment' => [
                    'label' => $sentimentLabel,
                    'positive_pct' => $posPercent,
                    'negative_pct' => $negPercent,
                    'neutral_pct' => $neuPercent
                ]
            ];
        }

        // 3. Simpan hasil analisis ke dalam tabel Cache biar request berikutnya instan
        NewsCache::updateOrCreate(
            ['country_code' => $countryCode, 'keyword' => $keyword],
            ['payload' => json_encode($analyzedArticles), 'updated_at' => Carbon::now()]
        );

        return $analyzedArticles;
    }
}