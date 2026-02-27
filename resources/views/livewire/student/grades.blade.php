<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\SIS\Enrollment;
use App\Models\LMS\Grade;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $enrollments;
    public $grades;

    public function mount()
    {
        $this->student = auth()->user();
        
        if ($this->student) {
            $this->enrollments = Enrollment::where('user_id', $this->student->id)
                ->with(['program', 'term'])
                ->get();
                
            $this->grades = Grade::whereIn('enrollment_id', $this->enrollments->pluck('id'))
                ->with(['course', 'enrollment.term', 'recorder'])
                ->orderBy('recorded_at', 'desc')
                ->get();
        } else {
            $this->enrollments = collect();
            $this->grades = collect();
        }
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">Academic Records & Grades</h1>
                <p class="mt-2 text-zinc-500">View your final course grades and academic progress.</p>
            </div>
            <div class="hidden sm:block">
                <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-primary-50 text-primary-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
        </div>
    </div>

    @if($grades->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <h3 class="text-lg font-medium text-zinc-900">No grades posted yet</h3>
            <p class="mt-1 text-zinc-500">Your instructors have not recorded any final grades for your active courses.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Academic Term</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Course</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-zinc-500 uppercase tracking-wider">Score</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-zinc-500 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Date Recorded</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-100">
                        @foreach($grades as $grade)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-zinc-900">{{ $grade->enrollment->term->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-zinc-900">{{ $grade->course->name ?? 'Course Not Found' }}</div>
                                    <div class="text-xs text-zinc-500 mt-1">Credits: {{ $grade->course->credits ?? 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-zinc-900">{{ $grade->percentage ? $grade->percentage . '%' : '--' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ in_array(strtoupper($grade->letter_grade), ['A', 'A+', 'A-']) ? 'bg-green-100 text-green-800' : (in_array(strtoupper($grade->letter_grade), ['B', 'B+', 'B-']) ? 'bg-blue-100 text-blue-800' : (in_array(strtoupper($grade->letter_grade), ['C', 'C+', 'C-']) ? 'bg-yellow-100 text-yellow-800' : (in_array(strtoupper($grade->letter_grade), ['F', 'D']) ? 'bg-red-100 text-red-800' : 'bg-zinc-100 text-zinc-800'))) }}">
                                        {{ $grade->letter_grade ?? 'P' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">
                                    {{ $grade->recorded_at ? $grade->recorded_at->format('M d, Y') : 'Pending' }}
                                    @if($grade->recorder)
                                        <div class="text-xs text-zinc-400 mt-0.5">by {{ $grade->recorder->name }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="bg-zinc-50 px-6 py-4 border-t border-zinc-200">
                <div class="flex items-center text-sm text-zinc-500">
                    <svg class="mr-1.5 h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    If you believe there is an error in your grades, please contact your instructor utilizing the Messages portal.
                </div>
            </div>
        </div>
    @endif
</div>
