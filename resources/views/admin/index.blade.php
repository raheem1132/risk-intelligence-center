@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Admin Dashboard</h2>
<p class="text-gray-400 mb-8">Kelola user, dataset pelabuhan, dan artikel analisis.</p>

<div class="grid md:grid-cols-3 gap-4 mb-8">
    <div class="p-5 bg-[#111827] border border-gray-800 rounded-xl"><span class="text-gray-500 text-xs">Users</span><strong class="block text-3xl text-white mt-2">{{ $users->count() }}</strong></div>
    <div class="p-5 bg-[#111827] border border-gray-800 rounded-xl"><span class="text-gray-500 text-xs">Ports shown</span><strong class="block text-3xl text-white mt-2">{{ $ports->count() }}</strong></div>
    <div class="p-5 bg-[#111827] border border-gray-800 rounded-xl"><span class="text-gray-500 text-xs">Articles</span><strong class="block text-3xl text-white mt-2">{{ $articles->count() }}</strong></div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
<section class="p-5 bg-[#111827] border border-gray-800 rounded-xl">
    <h3 class="font-bold text-white mb-4">Buat artikel analisis</h3>
    <form method="POST" action="{{ route('admin.articles.store') }}" class="space-y-3">@csrf
        <input name="title" required placeholder="Judul" class="w-full bg-gray-950 border border-gray-700 rounded p-3 text-white">
        <input name="author" placeholder="Penulis" class="w-full bg-gray-950 border border-gray-700 rounded p-3 text-white">
        <textarea name="content" required placeholder="Isi analisis" rows="5" class="w-full bg-gray-950 border border-gray-700 rounded p-3 text-white"></textarea>
        <button class="bg-emerald-600 px-4 py-2 rounded text-white font-bold">Simpan artikel</button>
    </form>
</section>
<section class="p-5 bg-[#111827] border border-gray-800 rounded-xl overflow-auto">
    <h3 class="font-bold text-white mb-4">User terbaru</h3>
    @foreach($users as $user)<div class="py-2 border-b border-gray-800 text-sm text-gray-300">{{ $user->name }} <span class="text-gray-500">{{ $user->email }}</span></div>@endforeach
</section>
<section class="p-5 bg-[#111827] border border-gray-800 rounded-xl overflow-auto">
    <h3 class="font-bold text-white mb-4">Artikel</h3>
    @foreach($articles as $article)<div class="flex justify-between py-2 border-b border-gray-800 text-sm text-gray-300"><span>{{ $article->title }}</span><form method="POST" action="{{ route('admin.articles.destroy', $article) }}">@csrf @method('DELETE')<button class="text-rose-400">Hapus</button></form></div>@endforeach
</section>
<section class="p-5 bg-[#111827] border border-gray-800 rounded-xl overflow-auto">
    <h3 class="font-bold text-white mb-4">Dataset pelabuhan</h3>
    @foreach($ports as $port)<div class="flex justify-between py-2 border-b border-gray-800 text-sm text-gray-300"><span>{{ $port->port_name }}</span><form method="POST" action="{{ route('admin.ports.destroy', $port) }}">@csrf @method('DELETE')<button class="text-rose-400">Hapus</button></form></div>@endforeach
</section>
</div>
@endsection
