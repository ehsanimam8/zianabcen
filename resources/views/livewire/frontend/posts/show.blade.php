<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Post;

new #[Layout('components.layouts.frontend')] class extends Component {
    public $post;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)->firstOrFail();
    }
    
    public function rendering($view)
    {
        $view->title($this->post->title . ' - Zainab Center')->layout('components.layouts.frontend');
    }
}; ?>

<div class="bg-white">
    <div class="px-4 py-16 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <a href="{{ route('frontend.posts.index') }}" class="inline-flex items-center text-sm font-medium text-zinc-500 hover:text-zinc-900 mb-8 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Announcements
        </a>

        <div class="mb-8">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 uppercase tracking-wider mb-4">
                {{ \Carbon\Carbon::parse($post->published_at)->format('F j, Y') }}
            </span>
            <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 leading-tight">{{ $post->title }}</h1>
        </div>

        @if($post->excerpt)
            <div class="text-xl text-zinc-500 mb-8 leading-relaxed font-medium">
                {{ $post->excerpt }}
            </div>
        @endif

        <div class="prose prose-lg prose-primary max-w-none text-zinc-700">
            {!! $post->content !!}
        </div>
        
        <div class="mt-16 pt-8 border-t border-zinc-200">
            <div class="flex items-center justify-between">
                <span class="text-sm text-zinc-500">Official Communication From</span>
                <span class="font-bold text-zinc-900">Zainab Center Administration</span>
            </div>
        </div>
    </div>
</div>
