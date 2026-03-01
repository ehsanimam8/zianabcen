<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\SIS\Program;
use Illuminate\Support\Facades\Session;

new #[Layout('components.layouts.frontend', ['title' => 'Islamic Programs | Zainab Center', 'description' => 'Explore our comprehensive Islamic programs, designed to guide students through classical curriculums and structured learning.'])] class extends Component {
    public $programs;

    public function mount()
    {
        $this->programs = Program::where('is_active', true)->get();
    }
    
    public function enroll($programId)
    {
        $cartIds = Session::get('cart', []);
        
        if (!in_array($programId, $cartIds)) {
            $cartIds[] = $programId;
            Session::put('cart', $cartIds);
        }
        
        return redirect()->route('frontend.cart');
    }
}; ?>

<div class="px-4 py-16 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4">Academic Programs</h1>
        <p class="text-lg text-zinc-500 max-w-2xl mx-auto">Explore our structured pathways designed to cultivate deep understanding of Islamic sciences, language, and spiritual growth.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($programs as $program)
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8 flex flex-col h-full hover:shadow-md transition-shadow">
                <div class="text-xs font-semibold tracking-wide uppercase text-primary-600 mb-2">
                    {{ $program->level ?? 'Open Level' }}
                </div>
                <h3 class="text-2xl font-bold text-zinc-900 mb-3">{{ $program->name }}</h3>
                <div class="prose prose-sm text-zinc-500 mb-6 flex-grow line-clamp-4">
                    {!! strip_tags($program->description) !!}
                </div>
                
                <div class="flex items-end justify-between mt-auto pt-6 border-t border-zinc-100">
                    <div>
                        <div class="text-2xl font-bold text-primary-800">${{ number_format($program->price, 2) }}</div>
                        <div class="text-xs text-zinc-500 uppercase font-medium mt-1">{{ Str::title(str_replace('_', ' ', $program->billing_cycle)) }}</div>
                    </div>
                    <div>
                        <a href="{{ route('frontend.programs.show', $program->id) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium text-sm transition-colors flex items-center">
                            View Courses <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
