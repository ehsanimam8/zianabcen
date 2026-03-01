<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Event;

new #[Layout('components.layouts.frontend', ['title' => 'Upcoming Events | Zainab Center', 'description' => 'Join our upcoming community events, workshops, and intensive courses at Zainab Center.'])] class extends Component {
    public $events;

    public function mount()
    {
        $this->events = Event::where('event_start', '>=', now())
            ->orderBy('event_start', 'asc')
            ->get();
    }
}; ?>

<div class="px-4 py-16 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4">Upcoming Events</h1>
        <p class="text-lg text-zinc-500 max-w-2xl mx-auto">Join our community gatherings, academic seminars, and spiritual retreats across the year.</p>
    </div>

    @if($events->isEmpty())
        <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-zinc-100">
            <svg class="mx-auto h-12 w-12 text-zinc-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-zinc-900">No events currently scheduled</h3>
            <p class="mt-1 text-sm text-zinc-500">Please check back soon for our upcoming calendar of events insha'Allah.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                    <div class="h-48 bg-zinc-100 relative">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-800 opacity-90 text-white">
                                <span class="text-lg font-bold tracking-widest uppercase">Zainab Center</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white px-3 py-1.5 rounded-lg shadow-sm text-center">
                            <div class="text-xs font-bold text-zinc-500 uppercase">{{ \Carbon\Carbon::parse($event->event_start)->format('M') }}</div>
                            <div class="text-xl font-bold text-primary-700 leading-none">{{ \Carbon\Carbon::parse($event->event_start)->format('d') }}</div>
                        </div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-xs text-zinc-500 mb-3 space-x-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($event->event_start)->format('g:i A') }}
                            </span>
                            @if($event->location)
                                <span class="flex items-center truncate max-w-[120px]">
                                    <svg class="w-4 h-4 mr-1 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $event->location }}
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-zinc-900 mb-3 line-clamp-2">
                            <a href="{{ route('frontend.events.show', $event->slug) }}" class="hover:text-primary-700 transition-colors">
                                {{ $event->title ?? 'Untitled Event' }}
                            </a>
                        </h3>
                        <div class="prose prose-sm text-zinc-500 mb-6 flex-grow line-clamp-3">
                            {!! strip_tags($event->description ?? '') !!}
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('frontend.events.show', $event->slug) }}" class="inline-flex items-center text-sm font-bold text-primary-600 hover:text-primary-800">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
