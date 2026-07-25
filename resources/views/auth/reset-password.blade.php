<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Password baru · SupplyGuard</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="grid min-h-screen place-items-center bg-[#080d14] p-5 text-slate-100">
<main class="w-full max-w-md rounded-3xl border border-white/10 bg-[#111927] p-8 shadow-2xl">
    <h1 class="text-2xl font-bold">Buat password baru</h1><p class="mt-2 text-sm text-slate-400">Gunakan minimal 8 karakter.</p>
    @if($errors->any())<div class="mt-5 rounded-xl border border-red-400/20 bg-red-400/10 p-3 text-sm text-red-200">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">@csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div><label class="mb-2 block text-sm">Email</label><input type="email" name="email" value="{{ old('email',$email) }}" required class="w-full rounded-xl border border-white/10 bg-[#09131f] px-4 py-3"></div>
        <div><label class="mb-2 block text-sm">Password baru</label><input type="password" name="password" required class="w-full rounded-xl border border-white/10 bg-[#09131f] px-4 py-3"></div>
        <div><label class="mb-2 block text-sm">Konfirmasi password</label><input type="password" name="password_confirmation" required class="w-full rounded-xl border border-white/10 bg-[#09131f] px-4 py-3"></div>
        <button class="w-full rounded-xl bg-emerald-400 px-4 py-3 font-bold text-emerald-950">Simpan password</button>
    </form>
</main>
</body></html>
