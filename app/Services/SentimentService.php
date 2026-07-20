<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SentimentService
{
    public function analyze(string $text): string
    {
        if (empty($text)) {
            return 'Neutral';
        }

        // Ambil kamus kata dari database
        $dictionaries = DB::table('sentiment_dictionaries')->get();
        
        $positiveScore = 0;
        $negativeScore = 0;
        $lowercaseText = strtolower($text);

        // Hitung kemunculan kata positif & negatif
        foreach ($dictionaries as $item) {
            $word = strtolower($item->word);
            $count = substr_count($lowercaseText, $word);

            if ($item->type === 'positive') {
                $positiveScore += $count;
            } elseif ($item->type === 'negative') {
                $negativeScore += $count;
            }
        }

        // Tentukan status akhir
        if ($positiveScore > $negativeScore) {
            return 'Positive';
        } elseif ($negativeScore > $positiveScore) {
            return 'Negative';
        }

        return 'Neutral';
    }
}