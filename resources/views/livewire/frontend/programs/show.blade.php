<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\SIS\Program;

new #[Layout('components.layouts.frontend')] class extends Component {
    public $program;

    public function mount($id)
    {
        $this->program = Program::with('courses')->findOrFail($id);
    }
    
    public function enroll()
    {
        $cartIds = \Illuminate\Support\Facades\Session::get('cart', []);
        
        if (!in_array($this->program->id, $cartIds)) {
            $cartIds[] = $this->program->id;
            \Illuminate\Support\Facades\Session::put('cart', $cartIds);
        }
        
        return redirect()->route('frontend.cart');
    }
    
    public function rendering($view)
    {
        $view->title($this->program->name . ' | Zainab Center')
             ->layout('components.layouts.frontend', [
                 'description' => \Illuminate\Support\Str::limit(strip_tags($this->program->description), 160)
             ]);
    }
}; ?>

<div class="bg-white">
    <!-- Header -->
    <div class="bg-zinc-50 border-b border-zinc-200">
        <div class="px-4 py-16 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <a href="{{ route('frontend.programs.index') }}" class="inline-flex items-center text-sm font-medium text-zinc-500 hover:text-zinc-900 mb-8 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Programs Directory
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-end">
                <div class="lg:col-span-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-primary-100 text-primary-800 mb-4">
                        {{ $program->level ?? 'Open Level' }}
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 leading-tight">{{ $program->name }}</h1>
                    <div class="mt-6 flex flex-wrap gap-4 text-sm text-zinc-500 font-medium">
                        <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $program->duration_months }} Months</span>
                        <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>{{ Str::title(str_replace('_', ' ', $program->billing_cycle)) }}</span>
                    </div>
                </div>
                
                <div class="lg:col-span-1 border-t lg:border-t-0 lg:border-l border-zinc-200 pt-8 lg:pt-0 lg:pl-8">
                    <div class="text-3xl font-bold text-zinc-900">${{ number_format($program->price, 2) }}</div>
                    <p class="text-sm text-zinc-500 mb-6">Inclusive base tuition rate.</p>
                    <button wire:click="enroll" class="w-full flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Enroll in Program &rarr;
                    </button>
                    <p class="mt-4 text-xs text-center text-zinc-400">Includes complete access to all aligned courses.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="px-4 py-16 sm:px-6 lg:px-8 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-16">
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold text-zinc-900 mb-8">Program Overview</h2>
            <div class="prose prose-lg text-zinc-600 max-w-none">
                {!! $program->description !!}
            </div>

            @if($program->prerequisites)
                <h3 class="text-xl font-bold text-zinc-900 mt-12 mb-4 border-b border-zinc-100 pb-2">Prerequisites</h3>
                <div class="prose prose-lg text-zinc-600">
                    {{ $program->prerequisites }}
                </div>
            @endif
        </div>
        
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-primary-50 rounded-2xl p-8 border border-primary-100">
                <h3 class="text-xl font-bold text-primary-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Aligned Courses
                </h3>
                
                @if($program->courses->isEmpty())
                    <p class="text-primary-700 text-sm italic">Curriculum is currently being aligned for this program.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($program->courses as $course)
                            <li class="flex items-start">
                                <span class="bg-primary-200 text-primary-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">{{ $loop->iteration }}</span>
                                <div>
                                    <h4 class="font-bold text-primary-900">{{ $course->name }}</h4>
                                    <p class="text-sm text-primary-700 mt-1 line-clamp-2">{{ $course->description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            <div class="bg-zinc-50 rounded-2xl p-8 border border-zinc-100 text-center">
                <h4 class="font-bold text-zinc-900 mb-2">Have a question?</h4>
                <p class="text-sm text-zinc-500 mb-6">Our academic advisors are ready to help you navigate our programs.</p>
                <a href="#" class="inline-flex items-center text-sm font-bold text-primary-600 hover:text-primary-800">
                    Contact Us
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>
