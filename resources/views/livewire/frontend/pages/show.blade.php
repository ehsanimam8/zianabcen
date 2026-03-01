<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Post;

new #[Layout('components.layouts.frontend')] class extends Component {
    public $page;

    public function mount($slug)
    {
        $this->page = Post::where('slug', $slug)
                          ->where('post_type', 'Page')
                          ->where('status', 'Published')
                          ->firstOrFail();
    }
    
    public function rendering($view)
    {
        $view->title($this->page->title . ' | Zainab Center');
    }
}; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
        <div class="bg-primary-900 px-8 py-12 text-center text-white">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">{{ $page->title }}</h1>
            <div class="w-16 h-1 bg-warning-500 mx-auto rounded"></div>
        </div>
        <div class="p-8 md:p-12 prose prose-zinc prose-a:text-primary-600 max-w-none">
            {!! str($page->content)->markdown() !!}
        </div>
    </div>
</div>
