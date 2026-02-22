<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\SIS\Enrollment;
use App\Models\LMS\CourseSession;
use Carbon\Carbon;

new #[Layout('components.layouts.app', ['title' => 'Class Calendar'])] class extends Component {
    public $student;
    public $sessions = [];
    public $upcomingSessions = [];

    public function mount()
    {
        // For demonstration, login as student1
        $this->student = User::where('email', 'student1@example.com')->first();
        
        if ($this->student) {
            $enrollments = Enrollment::where('user_id', $this->student->id)
                ->with(['courseAccess.course'])
                ->get();
                
            $courseIds = [];
            foreach ($enrollments as $enrollment) {
                foreach ($enrollment->courseAccess as $access) {
                    if ($access->is_active && $access->course) {
                        $courseIds[] = $access->course->id;
                    }
                }
            }
            
            if (!empty($courseIds)) {
                $this->sessions = CourseSession::whereIn('course_id', $courseIds)
                    ->where('is_cancelled', false)
                    ->whereDate('session_date', '>=', Carbon::now()->startOfWeek())
                    ->with(['course', 'instructor'])
                    ->orderBy('session_date')
                    ->orderBy('session_start_time')
                    ->get();
                    
                $this->upcomingSessions = $this->sessions->filter(function($session) {
                    return Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->session_start_time)
                        ->isFuture();
                })->take(5);
            }
        }
    }
    
    public function formatTime($time)
    {
        return Carbon::parse($time)->format('g:i A');
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900">Class Calendar</h1>
            <p class="mt-2 text-zinc-500">View your upcoming live sessions and class schedule.</p>
        </div>
        <div class="mt-4 sm:mt-0 px-4 py-2 bg-primary-50 rounded-lg text-primary-700 font-medium text-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ date('l, F j, Y') }}
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Weekly View / List -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-zinc-900 mb-4">This Week's Schedule</h2>
            
            @if(count($sessions) === 0)
                <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8 text-center">
                    <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-zinc-900">No classes scheduled</h3>
                    <p class="mt-1 text-sm text-zinc-500">You don't have any classes scheduled for this week.</p>
                </div>
            @else
                <div class="space-y-4">
                    @php
                        $currentDate = '';
                    @endphp
                    
                    @foreach($sessions as $session)
                        @php
                            $sessionDate = \Carbon\Carbon::parse($session->session_date)->format('l, F j');
                        @endphp
                        
                        @if($sessionDate !== $currentDate)
                            <h3 class="font-semibold text-zinc-900 mt-6 mb-2 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-primary-500 mr-2"></span>
                                {{ $sessionDate }}
                                @if(\Carbon\Carbon::parse($session->session_date)->isToday())
                                    <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">Today</span>
                                @endif
                            </h3>
                            @php
                                $currentDate = $sessionDate;
                            @endphp
                        @endif
                        
                        <div class="bg-white border border-zinc-200 rounded-xl p-5 hover:border-primary-300 hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center gap-4">
                            <!-- Time Column -->
                            <div class="flex-shrink-0 w-32 border-l-4 border-primary-500 pl-4 py-1">
                                <div class="text-sm font-bold text-zinc-900">{{ $this->formatTime($session->session_start_time) }}</div>
                                <div class="text-xs text-zinc-500 mt-1">{{ $this->formatTime($session->session_end_time) }}</div>
                                <div class="text-xs font-medium text-primary-600 mt-2">{{ $session->timezone ?? 'EST' }}</div>
                            </div>
                            
                            <!-- Detail Column -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-bold text-zinc-900 truncate">{{ $session->course->name ?? 'Course Name' }}</h4>
                                <div class="mt-1 flex items-center text-sm text-zinc-500">
                                    <svg class="mr-1.5 h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Instructor: {{ $session->instructor->name ?? 'TBA' }}
                                </div>
                                <div class="mt-1 flex items-center text-xs text-zinc-500">
                                    @if($session->platform === 'zoom')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">Zoom Session</span>
                                    @elseif($session->platform === 'start_meeting')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mr-2">StartMeeting Session</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Column -->
                            <div class="flex-shrink-0 self-start sm:self-center">
                                @if($session->meeting_url)
                                    <a href="{{ $session->meeting_url }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        Join Class
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @else
                                    <button disabled class="inline-flex items-center justify-center px-4 py-2 border border-zinc-200 text-sm font-medium rounded-lg text-zinc-400 bg-zinc-50 cursor-not-allowed">
                                        Link Pending
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Up Next -->
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
                <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="text-lg font-bold text-zinc-900 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Up Next
                    </h3>
                </div>
                <div class="p-0">
                    @if(count($upcomingSessions) === 0)
                        <div class="p-6 text-center text-sm text-zinc-500">
                            No upcoming sessions in the immediate future.
                        </div>
                    @else
                        <ul class="divide-y divide-zinc-100">
                            @foreach($upcomingSessions as $upcoming)
                                <li class="p-4 hover:bg-zinc-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-zinc-900 truncate">{{ $upcoming->course->name }}</p>
                                            <p class="text-xs text-zinc-500 mt-1 flex items-center">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($upcoming->session_date)->format('M j') }} at {{ $this->formatTime($upcoming->session_start_time) }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            
            <!-- Quick Help -->
            <div class="bg-primary-50 rounded-2xl border border-primary-100 p-6">
                <h4 class="font-bold text-primary-900 mb-2">Need help joining?</h4>
                <p class="text-sm text-primary-700 mb-4">If you're having trouble accessing a live session, make sure you're logged into the correct Zoom or StartMeeting account.</p>
                <a href="#" class="text-sm font-medium text-primary-700 hover:text-primary-800 flex items-center">
                    Read the guide
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
        
    </div>
</div>
