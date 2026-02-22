<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Post;

new #[Layout('components.layouts.frontend', ['title' => 'Latest Announcements | Zainab Center', 'description' => 'Stay updated with the latest news, announcements, and articles from Zainab Center.'])] class extends Component {
    public $posts;

    public function mount()
    {
        $this->posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();
    }
}; ?>

<div class="px-4 py-16 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4">Announcements</h1>
        <p class="text-lg text-zinc-500 max-w-2xl mx-auto">Stay up-to-date with essential news, admission deadlines, and official Zainab Center updates.</p>
    </div>

    @if($posts->isEmpty())
        <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-zinc-100">
            <svg class="mx-auto h-12 w-12 text-zinc-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <h3 class="text-lg font-medium text-zinc-900">No recent announcements</h3>
            <p class="mt-1 text-sm text-zinc-500">There are no published announcements at this time.</p>
        </div>
    @else
        <div class="max-w-4xl mx-auto space-y-8">
            @foreach($posts as $post)
                <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-8">
                        <div class="flex items-center text-sm text-zinc-500 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 uppercase tracking-wider">
                                {{ \Carbon\Carbon::parse($post->published_at)->format('F j, Y') }}
                            </span>
                        </div>
                        
                        <a href="{{ route('frontend.posts.show', $post->slug) }}" class="block mt-2">
                            <h3 class="text-2xl font-bold text-zinc-900 hover:text-primary-800 transition-colors">{{ $post->title }}</h3>
                        </a>
                        
                        <div class="mt-4 prose prose-sm text-zinc-600 max-w-none line-clamp-4">
                            {!! strip_tags($post->content) !!}
                        </div>
                        
                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('frontend.posts.show', $post->slug) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 uppercase tracking-wide">Read Announcement &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
