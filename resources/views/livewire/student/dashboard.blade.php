<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\SIS\Enrollment;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $enrollments;

    public function mount()
    {
        $this->student = auth()->user();
        
        if ($this->student) {
            $this->enrollments = Enrollment::where('user_id', $this->student->id)
                ->with(['program', 'term', 'courseAccess.course.modules'])
                ->get();
        } else {
            $this->enrollments = collect();
        }
    }
}; ?>

<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8">
        <h1 class="text-3xl font-bold text-zinc-900">Welcome back, {{ $student?->name }}</h1>
        <p class="mt-2 text-zinc-500">Here is an overview of your academic progress for the current semester.</p>
    </div>

    @if($enrollments->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8 text-center">
            <h3 class="text-lg font-medium text-zinc-900">No active enrollments</h3>
            <p class="mt-1 text-zinc-500">You are not currently enrolled in any programs.</p>
        </div>
    @else
        <!-- My Programs -->
        <h2 class="text-xl font-bold text-zinc-900 mt-8 mb-4">My Programs & Courses</h2>
        
        <div class="space-y-6">
            @foreach($enrollments as $enrollment)
                <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
                    <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900">{{ $enrollment->program->name }}</h3>
                                <p class="text-sm text-zinc-500">Term: {{ $enrollment->term->name }} • Status: <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">{{ $enrollment->status }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-4">Accessible Courses</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enrollment->courseAccess as $access)
                                @if($access->is_active && $access->course)
                                    <div class="group relative bg-white border border-zinc-200 rounded-xl p-5 hover:border-primary-500 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start">
                                            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center mb-4 group-hover:bg-primary-500 group-hover:text-white transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 text-zinc-600">
                                                {{ $access->course->credits }} Credits
                                            </span>
                                        </div>
                                        <h5 class="text-lg font-bold text-zinc-900 mb-1">{{ $access->course->name }}</h5>
                                        <p class="text-sm text-zinc-500 line-clamp-2 mb-4">{{ $access->course->description }}</p>
                                        
                                        <div class="mt-4 pt-4 border-t border-zinc-100 flex items-center justify-between">
                                            <div class="flex items-center text-sm text-zinc-500">
                                                <svg class="mr-1.5 h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                {{ $access->course->modules->count() }} Modules
                                            </div>
                                            <a href="{{ route('student.course.viewer', $access->course->id) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Enter Class &rarr;</a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>