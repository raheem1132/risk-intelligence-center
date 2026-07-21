<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()?->id;
        $watchlists = $userId
            ? Watchlist::with(['country.economicIndicators'=>fn($q)=>$q->latest('year')->limit(1),'country.riskScores'=>fn($q)=>$q->latest()->limit(1)])->where('user_id', $userId)->latest()->get()
            : collect();

        return view('watchlist', [
            'watchlists' => $watchlists,
            'countries' => Country::orderBy('name')->get(['id', 'name', 'code_iso2', 'region', 'inflation_rate']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user(), 401);
        $data = $request->validate(['country_id' => ['required', 'exists:countries,id']]);
        Watchlist::firstOrCreate(['user_id' => $request->user()->id, 'country_id' => $data['country_id']]);
        return back()->with('status', 'Negara ditambahkan ke watchlist.');
    }

    public function destroy(Request $request, Watchlist $watchlist): RedirectResponse
    {
        abort_unless($request->user() && $watchlist->user_id === $request->user()->id, 403);
        $watchlist->delete();
        return back()->with('status', 'Negara dihapus dari watchlist.');
    }
}
