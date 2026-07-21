<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\SentimentDictionary;
use App\Models\NewsCache;
use Carbon\Carbon;
use App\Models\Article;

class NewsService
{
    /**
     * Mengambil berita dari GNews API dan menghitung skor sentimennya.
     */
    public function fetchAndAnalyzeNews($countryCode, $keyword = 'economy', bool $forceRefresh = false)
    {
        // 1. Cek dulu apakah ada cache berita untuk negara & keyword ini yang masih segar (< 3 jam)
        $cached = NewsCache::where('country_code', $countryCode)
                            ->where('keyword', $keyword)
                            ->first();

        if (!$forceRefresh && $cached?->payload && $cached->updated_at?->gte(Carbon::now()->subMinutes(10))) {
            $cachedArticles = json_decode($cached->payload, true) ?: [];
            if ($cachedArticles !== []) {
                return $cachedArticles;
            }
        }

        // 2. Jika tidak ada cache, tembak GNews API
        // Catatan: GNews menggunakan parameter 'q' untuk query pencarian berita
        $apiKey = config('services.gnews.key'); // Pastikan nanti disetting di .env
        if (blank($apiKey)) {
            return $this->fallbackArticles($keyword, $cached);
        }
        if (Cache::get('gnews-quota-exhausted')) return $this->fallbackArticles($keyword, $cached);
        $terms = preg_split('/[\s,]+/', strtolower($keyword), -1, PREG_SPLIT_NO_EMPTY);
        $query = implode(' OR ', array_slice(array_unique($terms), 0, 8));
        
        try {
            $response = Http::withoutVerifying()->timeout(8)->retry(2, 200, throw: false)->get("https://gnews.io/api/v4/search", [
                'q' => $query,
                'lang' => 'en',
                'max' => 10,
                'apikey' => $apiKey
            ]);
        } catch (\Throwable) {
            return $this->fallbackArticles($keyword, $cached);
        }

        if ($response->failed()) {
            if (in_array($response->status(), [403, 429], true)) Cache::put('gnews-quota-exhausted', true, now()->addHours(12));
            return $this->fallbackArticles($keyword, $cached);
        }

        $articles = $response->json()['articles'] ?? [];
        if ($articles === []) {
            return $this->fallbackArticles($keyword, $cached);
        }
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
                'source' => $article['source'] ?? ['name' => 'GNews'],
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
            ['title' => "Cached {$keyword} feed", 'description' => 'Aggregated GNews payload', 'payload' => json_encode($analyzedArticles), 'updated_at' => Carbon::now()]
        );

        return $analyzedArticles;
    }

    private function localArticles(string $keyword): array
    {
        $positiveWords = SentimentDictionary::where('type', 'positive')->pluck('word')->map(fn ($word) => strtolower($word))->all();
        $negativeWords = SentimentDictionary::where('type', 'negative')->pluck('word')->map(fn ($word) => strtolower($word))->all();

        $terms = array_slice(preg_split('/[\s,]+/', trim($keyword), -1, PREG_SPLIT_NO_EMPTY), 0, 8);
        return Article::query()
            ->when($terms, fn ($query) => $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) $q->orWhere('title', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%");
            }))
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (Article $article) use ($positiveWords, $negativeWords) {
                $words = preg_split('/\s+/', preg_replace('/[^a-z0-9\s]/i', '', strtolower($article->title.' '.$article->content)));
                $positive = count(array_intersect($words, $positiveWords));
                $negative = count(array_intersect($words, $negativeWords));
                $total = max(1, $positive + $negative);
                return [
                    'title' => $article->title,
                    'description' => str($article->content)->limit(240)->toString(),
                    'url' => null,
                    'image' => null,
                    'published_at' => $article->created_at?->toIso8601String(),
                    'sentiment' => [
                        'label' => $positive > $negative ? 'Positive' : ($negative > $positive ? 'Negative' : 'Neutral'),
                        'positive_pct' => round($positive / $total * 100),
                        'negative_pct' => round($negative / $total * 100),
                        'neutral_pct' => ($positive + $negative) === 0 ? 100 : 0,
                    ],
                    'source' => 'Local analysis article',
                ];
            })->all();
    }

    private function fallbackArticles(string $keyword, ?NewsCache $preferred = null): array
    {
        $preferredPayload = $preferred?->payload ? json_decode($preferred->payload, true) : [];
        if (is_array($preferredPayload) && $preferredPayload !== []) return $preferredPayload;

        $cachedArticles = NewsCache::whereNotNull('payload')->latest('updated_at')->limit(12)->get()
            ->flatMap(fn (NewsCache $cache) => json_decode($cache->payload, true) ?: [])
            ->filter(fn ($article) => filled($article['title'] ?? null))
            ->unique('title')->take(10)->values()->all();

        return $cachedArticles !== [] ? $cachedArticles : $this->localArticles($keyword);
    }
}
