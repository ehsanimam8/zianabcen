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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
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
                    <div x-data="{ open: false }" class="relative z-50">
                        <button @click="open = !open" @click.away="open = false" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors flex items-center gap-1">
                            About <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity class="absolute left-0 mt-2 w-48 bg-white border border-zinc-200 rounded-lg shadow-lg py-2" style="display: none;">
                            <a href="{{ url('/about/about-us') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-primary-600">About Us</a>
                            <a href="{{ url('/about/our-founders') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-primary-600">Our Founders</a>
                            <a href="{{ url('/about/our-faculty') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-primary-600">Our Faculty</a>
                            <a href="{{ url('/about/our-services') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-primary-600">Our Services</a>
                            <a href="{{ url('/about/faqs') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-primary-600">FAQs</a>
                        </div>
                    </div>
                    <a href="{{ route('frontend.programs.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Programs</a>
                    <a href="{{ route('frontend.events.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Events</a>
                    <a href="{{ route('frontend.posts.index') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium transition-colors">Announcements</a>
                    <div class="pl-4 border-l border-zinc-200 flex items-center space-x-4">
                        <a href="{{ route('frontend.cart') }}" class="text-zinc-500 hover:text-primary-800 text-sm font-medium flex items-center transition-colors">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Enroll (<span x-data="{ count: {{ count(Session::get('cart', [])) }} }" x-text="count">{{ count(Session::get('cart', [])) }}</span>)
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

    <footer class="border-t border-zinc-200 bg-white pt-12 pb-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Column 1: Contact Us -->
                <div>
                    <h3 class="text-lg font-bold text-primary-800 mb-4">Contact Us</h3>
                    <ul class="text-zinc-600 text-sm space-y-2">
                        <li><a href="mailto:info@zainabcenter.org" class="hover:text-primary-500 transition-colors">info@zainabcenter.org</a></li>
                        <li><a href="tel:+19193391177" class="hover:text-primary-500 transition-colors">+1 (919) 339-1177</a></li>
                        <li class="pt-2">1823 Woodland Ave,<br>Edison, NJ 08820</li>
                    </ul>
                </div>
                
                <!-- Column 2: Sponsor Our Efforts -->
                <div>
                    <h3 class="text-lg font-bold text-primary-800 mb-4">Sponsor Our Efforts</h3>
                    <p class="text-zinc-600 text-sm mb-2">Support your organization serving the Muslim community since 2003.</p>
                    <p class="text-xs text-zinc-500 italic mb-4">(Any sponsorship is NOT tax exempt)</p>
                    <div class="flex items-center space-x-2">
                        <svg class="h-8 w-auto text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4C2.89 4 2.01 4.89 2.01 6L2 18C2 19.11 2.89 20 4 20H20C21.11 20 22 19.11 22 18V6C22 4.89 21.11 4 20 4ZM20 18H4V12H20V18ZM20 8H4V6H20V8Z"></path></svg>
                        <span class="text-sm font-medium text-zinc-700">Secure Payments via Stripe</span>
                    </div>
                </div>

                <!-- Column 3: Social Media -->
                <div>
                    <h3 class="text-lg font-bold text-primary-800 mb-4">Connect With Us</h3>
                    <div class="flex flex-wrap gap-4">
                        <a title="YouTube" href="https://youtube.com/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-red-600 transition-colors"> 
                            <i class="fa-brands fa-youtube text-2xl"></i>
                        </a> 
                        <a title="Instagram" href="https://instagram.com/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-pink-600 transition-colors"> 
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </a> 
                        <a title="TikTok" href="https://www.tiktok.com/@zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-black transition-colors"> 
                            <i class="fa-brands fa-tiktok text-2xl"></i>
                        </a> 
                        <a title="Facebook" href="https://facebook.com/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-blue-600 transition-colors"> 
                            <i class="fa-brands fa-facebook text-2xl"></i>
                        </a> 
                        <a title="X (Twitter)" href="https://twitter.com/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-black transition-colors"> 
                            <i class="fa-brands fa-x-twitter text-2xl"></i>
                        </a> 
                        <a title="Telegram" href="https://t.me/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-blue-500 transition-colors"> 
                            <i class="fa-brands fa-telegram text-2xl"></i>
                        </a> 
                        <a title="Twitch" href="https://twitch.tv/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-purple-600 transition-colors"> 
                            <i class="fa-brands fa-twitch text-2xl"></i>
                        </a> 
                        <a title="Mixlr" href="https://zainabcenter.mixlr.com" target="_blank" rel="noopener" class="text-zinc-400 hover:text-orange-500 transition-colors"> 
                            <i class="fa-solid fa-podcast text-2xl"></i>
                        </a> 
                        <a title="LinkedIn" href="https://www.linkedin.com/company/zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-blue-700 transition-colors"> 
                            <i class="fa-brands fa-linkedin text-2xl"></i>
                        </a>
                        <a title="Threads" href="https://www.threads.net/@zainabcenter" target="_blank" rel="noopener" class="text-zinc-400 hover:text-black transition-colors"> 
                            <i class="fa-brands fa-threads text-2xl"></i>
                        </a>
                        <a title="BlueSky" href="https://bsky.app/profile/zainabcenter.bsky.social" target="_blank" rel="noopener" class="text-zinc-400 hover:text-blue-400 transition-colors"> 
                            <i class="fa-brands fa-bluesky text-2xl"></i>
                        </a>
                        <a title="Pinterest" href="https://www.pinterest.com/zainabcenterinternational" target="_blank" rel="noopener" class="text-zinc-400 hover:text-red-600 transition-colors"> 
                            <i class="fa-brands fa-pinterest text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-zinc-200 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-zinc-500">
                <p>&copy; {{ date('Y') }} Zainab Center. Assalamu Alaikum Warahmatullah.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-primary-800 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-primary-800 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
