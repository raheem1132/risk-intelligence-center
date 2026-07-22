<!DOCTYPE html>
<html lang="en" class="h-full bg-[#080d14]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080d14">
    <title>Create account — SupplyGuard Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } }</script>
    <style>
        body::before { content: ''; position: fixed; inset: 0; pointer-events: none; background: radial-gradient(circle at 50% 38%, rgba(16,185,129,.08), transparent 32rem), linear-gradient(rgba(255,255,255,.012) 1px, transparent 1px), linear-gradient(90deg,rgba(255,255,255,.012) 1px,transparent 1px); background-size: auto,48px 48px,48px 48px; mask-image: linear-gradient(to bottom,black,transparent 80%); }
    </style>
</head>
<body class="relative flex min-h-full items-center justify-center overflow-x-hidden p-5 font-sans text-slate-100 antialiased sm:p-8">
    <main class="relative grid w-full max-w-[980px] items-stretch gap-4 lg:grid-cols-[440px_1fr]">
        <div class="absolute -inset-px rounded-[25px] bg-gradient-to-b from-emerald-400/20 via-slate-700/20 to-transparent blur-[1px]"></div>

        <section class="relative overflow-hidden rounded-3xl border border-white/[.07] bg-[#111927]/95 px-6 py-8 shadow-2xl shadow-black/40 backdrop-blur-xl sm:px-10 sm:py-10" aria-labelledby="register-title">
            <header class="text-center">
                <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl border border-emerald-400/20 bg-emerald-400/[.08] shadow-lg shadow-emerald-950/30">
                    <svg class="h-7 w-7 text-emerald-400" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 5.5 5.6v5.1c0 4.5 2.7 8.5 6.5 10.3 3.8-1.8 6.5-5.8 6.5-10.3V5.6L12 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m9.2 11.8 1.8 1.8 3.9-4.1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h1 id="register-title" class="text-2xl font-bold tracking-[-0.03em] text-white">Create your account</h1>
                <p class="mt-2 text-sm leading-6 text-slate-400">Start monitoring your global supply chain</p>
            </header>

            @if ($errors->any())
                <div class="mt-5 flex gap-3 rounded-xl border border-red-400/20 bg-red-400/[.08] p-3.5 text-sm text-red-200" role="alert">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.5 3.5a1.73 1.73 0 0 1 3 0l6.1 10.6a1.73 1.73 0 0 1-1.5 2.6H3.9a1.73 1.73 0 0 1-1.5-2.6L8.5 3.5ZM10 7a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 10 7Zm0 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="register-form" class="mt-7 space-y-4" action="/register" method="POST">
                @csrf
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Full name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required autofocus value="{{ old('name') }}" placeholder="Enter your full name" class="w-full rounded-xl border border-white/[.08] bg-[#0a101a] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 hover:border-white/[.14] focus:border-emerald-400/70 focus:ring-4 focus:ring-emerald-400/10">
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="name@gmail.com" class="w-full rounded-xl border border-white/[.08] bg-[#0a101a] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 hover:border-white/[.14] focus:border-emerald-400/70 focus:ring-4 focus:ring-emerald-400/10">
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-300">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8" placeholder="Minimum 8 characters" class="w-full rounded-xl border border-white/[.08] bg-[#0a101a] py-3 pl-4 pr-12 text-sm text-white outline-none transition placeholder:text-slate-600 hover:border-white/[.14] focus:border-emerald-400/70 focus:ring-4 focus:ring-emerald-400/10">
                        <button type="button" data-password-toggle="password" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-500 transition hover:text-slate-300" aria-label="Show password"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg></button>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8" placeholder="Repeat your password" class="w-full rounded-xl border border-white/[.08] bg-[#0a101a] px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 hover:border-white/[.14] focus:border-emerald-400/70 focus:ring-4 focus:ring-emerald-400/10">
                </div>
                <button id="submit-button" type="submit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3.5 text-sm font-semibold text-[#052e25] shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/20 disabled:cursor-wait disabled:opacity-70">
                    <svg id="loading-icon" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle class="opacity-30" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    <span id="button-label">Create account</span>
                </button>
            </form>

            <footer class="mt-6 border-t border-white/[.06] pt-5 text-center">
                <p class="text-sm text-slate-500">Already have an account? <a href="/" class="font-medium text-emerald-400 transition hover:text-emerald-300">Sign in</a></p>
                <p class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-slate-600"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Protected with enterprise-grade security</p>
            </footer>
        </section>

        <aside class="group relative hidden min-h-[720px] overflow-hidden rounded-3xl border border-white/[.07] bg-[#0a111c] shadow-2xl shadow-black/40 lg:block" aria-label="Global supply chain intelligence">
            <img src="{{ asset('images/supply-chain-globe.png') }}" alt="Global supply-chain network connecting ports and shipping routes" loading="lazy" decoding="async" fetchpriority="low" class="absolute inset-0 h-full w-full object-cover object-center transition duration-1000 ease-out group-hover:scale-[1.025]">
            <div class="absolute inset-0 bg-gradient-to-b from-[#07101b]/75 via-transparent to-[#07101b]/95"></div>
            <div class="absolute left-8 right-8 top-8"><span class="inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-[#07131c]/70 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[.16em] text-emerald-300 backdrop-blur-md"><span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span></span>Global network online</span></div>
            <div class="absolute inset-x-0 bottom-0 p-8">
                <div class="mb-5 h-px w-12 bg-emerald-400"></div>
                <h2 class="max-w-md text-2xl font-semibold leading-tight tracking-[-0.025em] text-white">Join a smarter approach to supply-chain risk.</h2>
                <p class="mt-3 max-w-md text-sm leading-6 text-slate-400">Create your account, then sign in securely to access global routes, disruptions, and trade intelligence.</p>
            </div>
        </aside>
    </main>

    <script>
        document.querySelector('[data-password-toggle]').addEventListener('click', event => {
            const button = event.currentTarget;
            const input = document.getElementById(button.dataset.passwordToggle);
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            button.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
            input.focus();
        });
        document.getElementById('register-form').addEventListener('submit', () => {
            document.getElementById('submit-button').disabled = true;
            document.getElementById('loading-icon').classList.remove('hidden');
            document.getElementById('button-label').textContent = 'Creating account...';
        });
    </script>
</body>
</html>
