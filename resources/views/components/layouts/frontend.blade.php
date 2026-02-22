<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Zainab Center | Traditional Islamic Education' }}</title>
    <meta name="description" content="{{ $description ?? 'Zainab Center offers comprehensive Islamic education programs and online courses for classical learning.' }}">
    <meta name="keywords" content="Islamic education, Zainab Center, online courses, Islamic programs, classical learning">
    <meta property="og:title" content="{{ $title ?? 'Zainab Center' }}">
    <meta property="og:description" content="{{ $description ?? 'Zainab Center offers comprehensive Islamic education programs and online courses for classical learning.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="/logo.png" as="image">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f8f1fb',
                            100: '#efdff6',
                            200: '#e0c2ec',
                            300: '#ca9bdd',
                            400: '#b16dc9',
                            500: '#9544b0',
                            600: '#7a3191',
                            700: '#642576',
                            800: '#5d0080',
                            900: '#472051',
                            950: '#2e0e37',
                            DEFAULT: '#5d0080'
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="bg-zinc-50 flex flex-col min-h-screen text-zinc-900">

    <!-- Navigation -->
    <nav class="bg-white border-b border-zinc-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/logo.png" alt="Zainab Center" class="h-10 w-auto mr-3">
                    <span class="font-bold text-xl tracking-tight text-primary-800">ZAINAB CENTER</span>
                </a>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('frontend.programs.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Programs</a>
                    <a href="{{ route('frontend.events.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Events</a>
                    <a href="{{ route('frontend.posts.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Announcements</a>
                    <div class="pl-4 border-l border-zinc-200 flex items-center space-x-4">
                        <a href="{{ route('frontend.cart') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium flex items-center transition-colors">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Cart (<span x-data="{ count: {{ count(Session::get('cart', [])) }} }" x-text="count">{{ count(Session::get('cart', [])) }}</span>)
                        </a>
                        <a href="/admin" class="text-sm font-medium text-white bg-primary-800 hover:bg-opacity-90 px-4 py-2 rounded-md transition-all">Portal Login</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
        {{ $slot }}
    </main>

    <footer class="border-t border-zinc-200 bg-white py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <p class="text-sm text-zinc-500">&copy; {{ date('Y') }} Zainab Center. Assalamu Alaikum Warahmatullah.</p>
            <div class="flex space-x-6 text-sm text-zinc-500">
                <a href="#" class="hover:text-zinc-900 transition-colors">Privacy</a>
                <a href="#" class="hover:text-zinc-900 transition-colors">Terms</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
