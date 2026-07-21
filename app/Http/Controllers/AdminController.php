<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Port;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403, 'Admin access required.');
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);
        return view('admin.index', [
            'users' => User::latest()->limit(50)->get(),
            'ports' => Port::latest()->limit(50)->get(),
            'articles' => Article::latest()->limit(50)->get(),
        ]);
    }

    public function storeArticle(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['title' => ['required', 'max:255'], 'content' => ['required'], 'author' => ['nullable', 'max:255']]);
        Article::create($data);
        return back()->with('status', 'Artikel dibuat.');
    }

    public function destroyArticle(Request $request, Article $article): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $article->delete();
        return back();
    }

    public function destroyPort(Request $request, Port $port): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $port->delete();
        return back();
    }
}
