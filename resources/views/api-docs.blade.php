@extends('layouts.app')
@section('content')
<h2 class="text-2xl font-bold text-white">REST API Documentation</h2><p class="text-sm text-gray-400 mb-6">Semua response menggunakan JSON. Ganti <code>{code}</code> dengan ISO2 seperti ID, DE, atau AU.</p>
@php $endpoints=[['GET','/api/overview','Statistik platform'],['GET','/api/countries','Daftar negara'],['GET','/api/countries/{code}','Detail negara'],['POST','/api/countries/{code}/sync','Sinkronkan seluruh API eksternal'],['GET','/api/countries/{code}/economy','GDP, inflasi, populasi historis'],['GET','/api/countries/{code}/weather','Snapshot cuaca'],['GET','/api/countries/{code}/currency-trend','Histori kurs'],['GET','/api/countries/{code}/risk-trend','Histori risk score'],['GET','/api/risk?country=ID&refresh=1','Weighted risk calculation'],['GET','/api/news?country=ID','News dan sentiment'],['GET','/api/currency?base=USD','Kurs live'],['GET','/api/ports?q=Singapore','Cari pelabuhan'],['GET','/api/ports/{id}','Detail pelabuhan'],['POST','/api/ports','Tambah pelabuhan'],['PUT','/api/ports/{id}','Update pelabuhan'],['DELETE','/api/ports/{id}','Hapus pelabuhan']]; @endphp
<div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden"><table class="w-full text-sm"><tbody class="divide-y divide-gray-800">@foreach($endpoints as $e)<tr><td class="p-4"><span class="text-xs font-bold {{ $e[0]==='GET'?'text-emerald-400':'text-amber-400' }}">{{ $e[0] }}</span></td><td class="p-4 font-mono text-gray-200">{{ $e[1] }}</td><td class="p-4 text-gray-500">{{ $e[2] }}</td></tr>@endforeach</tbody></table></div>
<div class="mt-6 p-5 rounded-xl border border-gray-800 bg-[#111827]"><h3 class="font-bold text-white">Setup data</h3><pre class="text-sm text-emerald-400 mt-3 overflow-auto">php artisan migrate
php artisan db:seed
php artisan ports:import-wpi
php artisan ports:import-unlocode --target=12000
php artisan data:sync ID
php artisan admin:promote your@email.com</pre></div>
@endsection
