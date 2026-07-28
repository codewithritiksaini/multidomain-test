<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Multi-Tenant Blog')</title>

    <!-- Vite Assets (Tailwind CSS v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS Fallback CDN for instant preview -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Header Navigation -->
    <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-md sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo / Brand -->
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-600 flex items-center justify-center font-extrabold text-xl text-white shadow-md shadow-indigo-500/20">
                        L
                    </div>
                    <span class="font-bold text-lg tracking-tight text-slate-900">
                        @yield('brand', 'MultiTenant SaaS')
                    </span>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @yield('nav_links')
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Laravel Dynamic Subdomain System. All rights reserved.</p>
    </footer>

</body>
</html>
