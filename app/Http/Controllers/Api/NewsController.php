<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request, NewsService $news): JsonResponse
    {
        $data = $news->fetchAndAnalyzeNews(
            strtoupper($request->string('country', 'ID')->toString()),
            $request->string('keyword', 'logistics')->toString()
        );

        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
