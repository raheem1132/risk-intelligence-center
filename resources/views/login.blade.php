<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0B0F17]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SupplyGuard Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md space-y-8 bg-[#111827] border border-gray-800/80 p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-950/50 border border-emerald-900/50 text-emerald-400 text-2xl mb-3">
                🛡️
            </div>
            <h2 class="text-xl font-bold tracking-tight text-white">SupplyGuard Pro</h2>
            <p class="text-xs text-gray-400 mt-1.5">Risk Intelligence Command Center</p>
        </div>

        <!-- Form Login Mengarah ke Controller Lek -->
        <form class="space-y-5" action="/login" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Email Address</label>
                <input id="email" name="email" type="email" required value="dodomas@supplyguard.pro" 
                    class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-medium text-gray-400">Password</label>
                    <a href="#" class="text-[11px] text-emerald-400 hover:underline">Forgot password?</a>
                </div>
                <input id="password" name="password" type="password" required value="••••••••" 
                    class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" checked class="h-4 w-4 rounded border-gray-800 bg-[#0B0F17] text-emerald-600 accent-emerald-500 focus:ring-0">
                <label for="remember-me" class="ml-2 block text-xs text-gray-400 select-none">Remember this station</label>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-3 rounded-lg transition uppercase tracking-wider">
                Establish Connection
            </button>
        </form>

        <div class="text-center border-t border-gray-800/60 pt-4 text-xs text-gray-400">
            Don't have an account? <a href="/register" class="text-emerald-400 font-medium hover:underline">Create Account</a>
        </div>
    </div>

</body>
</html>