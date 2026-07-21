<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $base = strtoupper($request->string('base', 'USD')->toString());
        $rates = Cache::remember("currency-rates-{$base}", now()->addHour(), function () use ($base) {
            $response = Http::withoutVerifying()->timeout(8)->retry(2, 200)->get("https://open.er-api.com/v6/latest/{$base}");
            return $response->successful() ? ($response->json('rates') ?? []) : [];
        });

        if ($rates === []) {
            return response()->json([
                'status' => 'unavailable',
                'message' => 'Live exchange-rate provider is temporarily unavailable.',
                'base' => $base,
                'rates' => [],
            ], 503);
        }

        $symbols = $request->filled('symbols')
            ? array_filter(array_map('strtoupper', explode(',', $request->string('symbols')->toString())))
            : [];

        if ($symbols !== []) {
            $rates = array_intersect_key($rates, array_flip($symbols));
        }

        return response()->json([
            'status' => 'success',
            'base' => $base,
            'updated_at' => now()->toIso8601String(),
            'rates' => $rates,
        ]);
    }
}
