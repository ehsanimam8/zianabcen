<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\SIS\Course;
use App\Models\User;

new #[Layout('components.layouts.app')] class extends Component {
    public $course;
    public $student;
    
    public $activeLesson = null;
    public $activeAssessment = null;
    public $completedLessonIds = [];

    public function mount(Course $course)
    {
        $this->student = auth()->user();
        
        // Eager load modules AND their published lessons ordered by sequence
        $this->course = Course::with([
            'modules' => function($q) {
                $q->where('is_published', true)->orderBy('sequence');
            }, 
            'modules.lessons' => function($q) {
                $q->where('is_published', true)->orderBy('sequence');
            },
            'assessments' => function($q) {
                $q->where('is_published', true)->orderBy('due_date');
            }
        ])->findOrFail($course->id);
        
        $this->loadProgress();

        // Optionally auto-select the first lesson
        if ($this->course->modules->isNotEmpty() && $this->course->modules->first()->lessons->isNotEmpty()) {
            $this->activeLesson = $this->course->modules->first()->lessons->first();
        }
    }
    
    public function loadProgress()
    {
        if ($this->student) {
            $this->completedLessonIds = \App\Models\LMS\LessonProgress::where('user_id', $this->student->id)
                ->where('status', 'completed')
                ->whereIn('lesson_id', $this->course->modules->flatMap->lessons->pluck('id'))
                ->pluck('lesson_id')
                ->toArray();
        }
    }

    public function markAsComplete($lessonId)
    {
        if (!$this->student) return;

        \App\Models\LMS\LessonProgress::updateOrCreate(
            ['user_id' => $this->student->id, 'lesson_id' => $lessonId],
            ['status' => 'completed', 'completed_at' => now()]
        );

        $this->loadProgress();
    }
    
    public function selectLesson($lessonId, $moduleId)
    {
        $module = $this->course->modules->firstWhere('id', $moduleId);
        if ($module) {
            $this->activeLesson = $module->lessons->firstWhere('id', $lessonId);
            $this->activeAssessment = null;
        }
    }

    public function selectAssessment($assessmentId)
    {
        $this->activeAssessment = $this->course->assessments->firstWhere('id', $assessmentId);
        $this->activeLesson = null;
    }
}; ?>

<div class="h-[calc(100vh-8rem)] flex shadow-sm border border-zinc-200 rounded-2xl overflow-hidden bg-white">
    
    <!-- Left Sidebar: Course Curriculum (Modules and Lessons) -->
    <div class="w-80 bg-zinc-50 border-r border-zinc-200 flex flex-col hidden md:flex">
        <div class="p-6 border-b border-zinc-200 bg-white">
            <h2 class="text-xl font-bold text-zinc-900">{{ $course->name }}</h2>
            <p class="text-sm text-zinc-500 mt-1">{{ $course->credits }} Credits</p>
            <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 mt-4 inline-flex items-center">
                &larr; Back to Dashboard
            </a>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            @forelse($course->modules as $module)
                <div class="border-b border-zinc-200">
                    <div class="bg-zinc-100/50 px-4 py-3 cursor-pointer">
                        <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Module {{ $module->sequence }}</span>
                        <h3 class="text-sm font-bold text-zinc-800">{{ $module->title }}</h3>
                    </div>
                    
                    <div class="bg-white">
                        @foreach($module->lessons as $lesson)
                            <button 
                                wire:click="selectLesson('{{ $lesson->id }}', '{{ $module->id }}')" 
                                class="w-full text-left px-4 py-3 flex items-start space-x-3 hover:bg-zinc-50 transition-colors {{ $activeLesson?->id === $lesson->id ? 'bg-primary-50 hover:bg-primary-50 border-l-2 border-primary-500' : 'border-l-2 border-transparent' }}"
                            >
                                <div class="mt-0.5">
                                    @if($lesson->type === 'video')
                                        <svg class="w-4 h-4 text-zinc-400 {{ $activeLesson?->id === $lesson->id ? 'text-primary-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($lesson->type === 'quiz')
                                        <svg class="w-4 h-4 text-zinc-400 {{ $activeLesson?->id === $lesson->id ? 'text-primary-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-zinc-400 {{ $activeLesson?->id === $lesson->id ? 'text-primary-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium {{ $activeLesson?->id === $lesson->id ? 'text-primary-800 font-semibold' : 'text-zinc-700' }} block truncate">
                                            {{ $lesson->title }}
                                        </span>
                                        <span class="text-xs text-zinc-400 capitalize">{{ $lesson->type }}</span>
                                    </div>
                                    @if(in_array($lesson->id, $completedLessonIds))
                                        <svg class="h-4 w-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-zinc-500 text-sm">
                    No curriculum modules available yet.
                </div>
            @endforelse

            @if($course->assessments->isNotEmpty())
                <div class="border-b border-zinc-200 mt-4">
                    <div class="bg-zinc-100/50 px-4 py-3 cursor-pointer">
                        <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1 block">Evaluations</span>
                        <h3 class="text-sm font-bold text-zinc-800">Assessments & Quizzes</h3>
                    </div>
                    
                    <div class="bg-white">
                        @foreach($course->assessments as $assessment)
                            <button 
                                wire:click="selectAssessment('{{ $assessment->id }}')" 
                                class="w-full text-left px-4 py-3 flex items-start space-x-3 hover:bg-zinc-50 transition-colors {{ $activeAssessment?->id === $assessment->id ? 'bg-indigo-50 hover:bg-indigo-50 border-l-2 border-indigo-500' : 'border-l-2 border-transparent' }}"
                            >
                                <div class="mt-0.5">
                                    <svg class="w-4 h-4 text-zinc-400 {{ $activeAssessment?->id === $assessment->id ? 'text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0 flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium {{ $activeAssessment?->id === $assessment->id ? 'text-indigo-800 font-semibold' : 'text-zinc-700' }} block truncate">
                                            {{ $assessment->title }}
                                        </span>
                                        <span class="text-xs text-zinc-400 capitalize">{{ $assessment->type }}</span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Main Content Area: Lesson Viewer -->
    <div class="flex-1 overflow-y-auto bg-white flex flex-col">
        @if($activeLesson)
            <div class="p-8 lg:p-12 max-w-4xl mx-auto w-full">
                <!-- Lesson Header -->
                <div class="mb-8 border-b border-zinc-100 pb-8">
                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-700 uppercase tracking-wide mb-4">
                        {{ $activeLesson->type }} Format
                    </div>
                    <h1 class="text-3xl font-extrabold text-zinc-900 tracking-tight">{{ $activeLesson->title }}</h1>
                </div>
                
                <!-- Lesson Content -->
                <div class="prose prose-zinc prose-primary max-w-none">
                    {!! $activeLesson->content !!}
                </div>
                
                @if($activeLesson->type === 'video' && $activeLesson->file_url)
                    @php
                        $isYoutube = false;
                        $youtubeId = '';
                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $activeLesson->file_url, $match)) {
                            $isYoutube = true;
                            $youtubeId = $match[1];
                        }
                    @endphp
                    <div class="mt-8 bg-black aspect-video rounded-xl overflow-hidden relative shadow-xl ring-1 ring-zinc-900/5">
                        @if($isYoutube)
                            <iframe class="w-full h-full border-0 absolute top-0 left-0" src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video class="w-full h-full absolute top-0 left-0 outline-none" controls controlsList="nodownload" preload="metadata">
                                <source src="{{ route('student.media.stream', ['lesson' => $activeLesson->id]) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                @endif
                
                @if($activeLesson->type === 'live_session' && $activeLesson->meeting_url)
                    <div class="mt-8 bg-primary-50 border border-primary-100 rounded-xl p-6 text-center">
                        <h4 class="text-lg font-bold text-primary-900 mb-2">Live Session Access</h4>
                        <p class="text-sm text-primary-700 mb-4">This session is conducted via remote meeting.</p>
                        <a href="{{ $activeLesson->meeting_url }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Join Meeting Now
                        </a>
                    </div>
                @endif

                <div class="mt-12 pt-8 border-t border-zinc-100 flex justify-between items-center">
                    <div>
                        @if(in_array($activeLesson->id, $completedLessonIds))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Completed
                            </span>
                        @else
                            <button wire:click="markAsComplete('{{ $activeLesson->id }}')" class="inline-flex items-center justify-center px-6 py-3 border border-zinc-300 shadow-sm text-sm font-medium rounded-lg text-zinc-700 bg-white hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                Mark as Complete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($activeAssessment)
            <div class="p-8 lg:p-12 max-w-4xl mx-auto w-full flex flex-col justify-center items-center h-full text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-6">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                
                <h2 class="text-3xl font-extrabold text-zinc-900 tracking-tight">{{ $activeAssessment->title }}</h2>
                <div class="inline-flex items-center px-3 py-1 mt-3 rounded-full text-sm font-semibold bg-zinc-100 text-zinc-700 uppercase tracking-wide">
                    {{ ucfirst($activeAssessment->type) }}
                </div>
                
                @if($activeAssessment->description)
                    <p class="mt-4 text-lg text-zinc-600 max-w-2xl">{{ $activeAssessment->description }}</p>
                @endif
                
                <div class="mt-8 flex items-center justify-center space-x-8 text-sm text-zinc-500">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Time Limit: {{ $activeAssessment->time_limit_minutes ? $activeAssessment->time_limit_minutes . ' mins' : 'None' }}
                    </div>
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Max Attempts: {{ $activeAssessment->max_attempts }}
                    </div>
                </div>
                
                <div class="mt-12">
                    <button class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md">
                        Begin Assessment
                    </button>
                    <p class="mt-3 text-sm text-zinc-500">Timer will start immediately.</p>
                </div>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center text-zinc-500 flex-col px-6 text-center h-full">
                <svg class="w-16 h-16 text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <p class="text-lg">Please select a lesson from the curriculum sidebar to begin.</p>
            </div>
        @endif
    </div>
</div>