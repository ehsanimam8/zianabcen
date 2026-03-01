<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Event;

new #[Layout('components.layouts.frontend')] class extends Component {
    public $event;

    public function mount($slug)
    {
        $this->event = Event::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
    }
    
    public function rendering($view)
    {
        $view->title($this->event->title . ' | Zainab Center')
             ->layout('components.layouts.frontend', [
                 'description' => \Illuminate\Support\Str::limit(strip_tags($this->event->description), 160)
             ]);
    }
}; ?>

<div class="bg-zinc-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('frontend.events.index') }}" class="inline-flex items-center text-sm font-medium text-zinc-500 hover:text-zinc-900 mb-8 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to All Events
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
            @if($event->image)
                <div class="h-64 sm:h-80 w-full relative">
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
            @endif
            
            <div class="p-8 sm:p-12">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-primary-100 text-primary-800">
                        {{ \Carbon\Carbon::parse($event->event_start)->format('M d, Y') }}
                    </span>
                    <span class="badge @if($event->status == 'Upcoming') bg-blue-100 text-blue-800 @elseif($event->status == 'Ongoing') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">
                        {{ $event->status }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-6">{{ $event->title }}</h1>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 bg-zinc-50 rounded-xl border border-zinc-100 mb-8">
                    <div>
                        <div class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Date & Time</div>
                        <div class="text-zinc-900 font-medium whitespace-pre-line">
                            Start: {{ \Carbon\Carbon::parse($event->event_start)->format('l, F j, Y \a\t g:i A') }}
                            End: {{ \Carbon\Carbon::parse($event->event_end)->format('l, F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    @if($event->location)
                    <div>
                        <div class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Location</div>
                        <div class="text-zinc-900 font-medium">{{ $event->location }}</div>
                    </div>
                    @endif
                    @if($event->meeting_url)
                    <div class="sm:col-span-2">
                        <div class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Virtual Meeting Link</div>
                        <a href="{{ $event->meeting_url }}" target="_blank" class="text-primary-600 hover:text-primary-800 font-medium truncate shrink">{{ $event->meeting_url }}</a>
                    </div>
                    @endif
                </div>

                @if($event->status !== 'Past')
                <div class="mb-8 p-6 bg-primary-50 border border-primary-200 rounded-xl flex flex-col sm:flex-row items-center justify-between shadow-sm">
                    <div>
                        <h3 class="text-lg font-bold text-primary-900">Are you ready to join us?</h3>
                        <p class="text-sm text-primary-700">Secure your spot for this beautiful event.</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('frontend.events.register', $event->slug ?? $event->id) }}" class="inline-flex justify-center rounded-md border border-transparent bg-primary-800 py-3 px-6 text-sm font-bold text-white shadow hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                            Register Now
                        </a>
                    </div>
                </div>
                @endif

                <div class="prose prose-zinc prose-a:text-primary-600 max-w-none">
                    {!! str($event->description)->markdown() !!}
                </div>
            </div>
        </div>
    </div>
</div>
