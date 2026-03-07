<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Student Dashboard' }}</title>
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
            .glassmorphism {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
        </style>
        @livewireStyles
    </head>
    <body class="bg-zinc-50 min-h-screen text-zinc-900 flex">

        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-zinc-200 hidden md:flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-zinc-200">
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-600">
                    Zainab Center
                    <span class="block text-xs font-semibold text-zinc-500 tracking-wider uppercase mt-0.5">Student Portal</span>
                </span>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('student.dashboard') ? 'bg-zinc-100 text-primary-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('student.calendar') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('student.calendar') ? 'bg-zinc-100 text-primary-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Class Calendar
                </a>
                <a href="{{ route('student.messages') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('student.messages') ? 'bg-zinc-100 text-primary-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Messages
                </a>
                <a href="{{ route('student.grades') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('student.grades') ? 'bg-zinc-100 text-primary-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Grades
                </a>
            </nav>
            <div class="p-4 border-t border-zinc-200">
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 text-sm font-medium text-zinc-600 rounded-lg hover:bg-zinc-100 group">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->name ?? 'Student') }}" alt="Profile">
                        <span class="ml-3 group-hover:text-zinc-900">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <button class="md:hidden text-zinc-500 hover:text-zinc-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center space-x-4 ml-auto">
                    <span class="text-sm border border-primary-200 bg-primary-50 text-primary-800 px-3 py-1 font-semibold rounded-full">Assalamu Alaikum, {{ auth()->user()?->name ?? 'Student' }}</span>
                </div>
            </header>

            <div class="flex-1 overflow-auto bg-zinc-50">
                <div class="px-4 py-8 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @livewireScripts
    </body>
</html>