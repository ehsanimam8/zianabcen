<x-layouts.frontend title="Welcome to Zainab Center" description="A dedicated platform uniting students and educators in the pursuit of Islamic knowledge, deeply rooted in the Quran and Sunnah.">
    <!-- Hero Section -->
    <div class="flex-grow flex flex-col items-center justify-center text-center px-4 py-16 sm:py-24">
        <div class="max-w-3xl mx-auto space-y-8">
            <div class="inline-block px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold tracking-wide uppercase mb-4 border border-primary-100">
                Bismillah ar-Rahman ar-Rahim
            </div>
            <h1 class="text-5xl sm:text-7xl font-bold tracking-tight text-zinc-900 leading-tight">
                Seek Knowledge, <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-400 block mt-2">Strengthen Faith.</span>
            </h1>
            <p class="mt-6 text-lg text-zinc-500 max-w-2xl mx-auto leading-relaxed">
                Welcome to Zainab Center. A dedicated platform uniting students and educators in the pursuit of Islamic knowledge, deeply rooted in the Quran and Sunnah.
            </p>
            
            <div class="pt-8 flex justify-center gap-4 flex-wrap">
                <a href="{{ route('frontend.programs.index') }}" class="px-8 py-3.5 border border-primary-200 bg-white hover:border-primary-300 text-primary-800 text-sm font-bold rounded-lg shadow-sm transition-all text-center min-w-[160px]">
                    Explore Programs
                </a>
                <a href="/admin" class="px-8 py-3.5 bg-primary-700 hover:bg-primary-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all text-center min-w-[160px]">
                    Student Portal &rarr;
                </a>
            </div>
            
            <div class="pt-6 flex items-center justify-center space-x-6 text-sm text-zinc-500 font-medium">
                 <a href="{{ route('frontend.events.index') }}" class="hover:text-primary-700 transition-colors">Upcoming Events</a>
                 <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                 <a href="{{ route('frontend.posts.index') }}" class="hover:text-primary-700 transition-colors">Latest Announcements</a>
            </div>
        </div>
        
        <!-- Feature Highlights -->
        <div class="max-w-6xl mx-auto px-4 mt-32 mb-10 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl border border-zinc-100 shadow-sm text-left hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-6 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Structured Programs</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Enroll in long-term academic tracts focusing on Arabic, Fiqh, Tafseer, and foundational Islamic sciences.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl border border-zinc-100 shadow-sm text-left hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-6 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Live Classes</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Join highly interactive live sessions via Zoom and StartMeeting safely managed within your portal.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl border border-zinc-100 shadow-sm text-left hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-6 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Dedicated Community</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Connecting diverse families, parents, and eager students together in a supportive academic framework.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl border border-zinc-100 shadow-sm text-left hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-6 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Spiritual Events</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Experience and join upcoming lectures, local retreats, and holistic spiritual events hosted by Zainab Center.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
