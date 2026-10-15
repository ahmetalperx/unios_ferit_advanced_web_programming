<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app() -> getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Projects') }} - {{ __('Advanced Web Programming') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-900 bg-[radial-gradient(#334155_1px,transparent_1px)] bg-[size:20px_20px] text-slate-300 antialiased" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <nav class="bg-slate-800/90 backdrop-blur-md border-b border-slate-700/60 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-teal-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-diagram-project text-lg"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">Project<span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-300">Manager</span></span>
            </a>

            <div class="flex items-center gap-6">
                <!-- Language Switcher -->
                <div class="flex items-center bg-slate-900/80 rounded-xl p-1 border border-slate-700/60">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ app() -> getLocale() === 'en' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">EN</a>
                    <a href="{{ route('lang.switch', 'pl') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ app() -> getLocale() === 'pl' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">PL</a>
                    <a href="{{ route('lang.switch', 'tr') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ app() -> getLocale() === 'tr' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">TR</a>
                </div>

                @auth
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2.5 bg-slate-800 px-3.5 py-1.5 rounded-xl border border-slate-700/50">
                            <i class="fa-solid fa-user-circle text-teal-400 text-lg"></i>
                            <span class="text-sm font-semibold text-white">{{ Auth::user() -> name }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-rose-400 text-sm font-semibold flex items-center gap-2 px-3 py-1.5 rounded-xl transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>{{ __('Logout') }}</span>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        @if (session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center gap-3 font-semibold shadow-lg backdrop-blur-md">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors -> any())
            <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex flex-col gap-1 shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ __('Please correct the errors below:') }}</span>
                </div>
                <ul class="list-disc list-inside text-sm text-rose-300">
                    @foreach ($errors -> all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
