<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Lupa password · SupplyGuard</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="grid min-h-screen place-items-center bg-[#080d14] p-5 text-slate-100">
<main class="w-full max-w-md rounded-3xl border border-white/10 bg-[#111927] p-8 shadow-2xl">
    <div class="mb-6"><a href="{{ route('login') }}" class="text-sm text-emerald-400">← Kembali ke login</a><h1 class="mt-5 text-2xl font-bold">Reset password</h1><p class="mt-2 text-sm leading-6 text-slate-400">Masukkan email akun. Kami akan mengirim tautan reset yang aman.</p></div>
    @if(session('status'))<div class="mb-5 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-3 text-sm text-emerald-200">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="mb-5 rounded-xl border border-red-400/20 bg-red-400/10 p-3 text-sm text-red-200">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">@csrf
        <div><label class="mb-2 block text-sm text-slate-300">Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-white/10 bg-[#09131f] px-4 py-3 outline-none focus:border-emerald-400"></div>
        <button class="w-full rounded-xl bg-emerald-400 px-4 py-3 font-bold text-emerald-950">Kirim tautan reset</button>
    </form>
</main>
</body></html>
