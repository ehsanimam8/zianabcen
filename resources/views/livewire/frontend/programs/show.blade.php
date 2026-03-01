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
    
    public function enroll($courseId)
    {
        $cartIds = \Illuminate\Support\Facades\Session::get('cart', []);
        
        if (!in_array($courseId, $cartIds)) {
            $cartIds[] = $courseId;
            \Illuminate\Support\Facades\Session::put('cart', $cartIds);
        }
        
        $this->redirect(route('frontend.cart'), navigate: true);
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
                    <h3 class="font-bold text-zinc-900 mb-2 border-b border-zinc-100 pb-2">Flexible Learning</h3>
                    <p class="text-sm text-zinc-500 mb-6">Choose and build your schedule by selecting from the individual aligned courses below. You only pay for the courses you enroll in.</p>
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
            
            <h3 class="text-2xl font-bold text-zinc-900 mt-16 mb-8 border-b border-zinc-100 pb-2 flex items-center">
                <svg class="w-8 h-8 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Aligned Courses
            </h3>
            
            @if($program->courses->isEmpty())
                <p class="text-zinc-500 italic">Curriculum is currently being aligned for this program.</p>
            @else
                <ul class="space-y-6">
                    @foreach($program->courses as $course)
                        <li class="bg-zinc-50 rounded-xl p-6 border border-zinc-100 flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex-grow pr-6 mb-4 md:mb-0">
                                <div class="flex items-center mb-2">
                                    <span class="bg-primary-100 text-primary-800 text-xs font-bold px-2 py-0.5 rounded mr-2">Course {{ $loop->iteration }}</span>
                                </div>
                                <h4 class="text-lg font-bold text-zinc-900 mb-1">{{ $course->name }}</h4>
                                <p class="text-sm text-zinc-500">{{ strip_tags($course->description) }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 shrink-0">
                                @if($course->price > 0)
                                    <span class="text-xl font-bold text-primary-800">${{ number_format($course->price, 2) }}</span>
                                @endif
                                @if(in_array($course->id, \Illuminate\Support\Facades\Session::get('cart', [])))
                                    <span class="px-4 py-2 bg-green-50 text-green-700 font-bold rounded-lg text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        In Cart
                                    </span>
                                @else
                                    <button wire:click="enroll('{{ $course->id }}')" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-bold hover:bg-primary-700 transition shadow-sm">
                                        Enroll in Course
                                    </button>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
        <div class="lg:col-span-1 space-y-8">
            
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
