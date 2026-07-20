<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0B0F17]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SupplyGuard Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md space-y-6 bg-[#111827] border border-gray-800/80 p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-950/50 border border-emerald-900/50 text-emerald-400 text-2xl mb-3">
                🛡️
            </div>
            <h2 class="text-xl font-bold tracking-tight text-white">Create Security Profile</h2>
            <p class="text-xs text-gray-400 mt-1.5">Request access to the Risk Intelligence network</p>
        </div>

        <!-- Form Register Mengarah ke Controller Lek -->
        <form class="space-y-4" action="/register" method="POST">
            @csrf
            <div>
                <label for="name" class="block text-xs font-medium text-gray-400 mb-1.5">Full Name</label>
                <input id="name" name="name" type="text" required placeholder="e.g. Dodo Mas" 
                    class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Corporate Email</label>
                <input id="email" name="email" type="email" required placeholder="name@company.com" 
                    class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-400 mb-1.5">Password</label>
                <input id="password" name="password" type="password" required placeholder="••••••••" 
                    class="w-full bg-[#0B0F17] border border-gray-800 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-emerald-500 transition">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-3 rounded-lg transition uppercase tracking-wider mt-2">
                Request Access Token
            </button>
        </form>

        <div class="text-center border-t border-gray-800/60 pt-4 text-xs text-gray-400">
            Already registered? <a href="/" class="text-emerald-400 font-medium hover:underline">Sign In here</a>
        </div>
    </div>

</body>
</html>