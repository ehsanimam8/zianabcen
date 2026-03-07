<?php

namespace App\Filament\Teacher\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TeacherDashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $teacherId = auth()->id();
        
        $myClasses = \App\Models\LMS\CourseSession::where('instructor_user_id', $teacherId)
            ->where('session_date', '>=', now()->startOfDay())
            ->count();
            
        // Count students enrolled in courses this teacher teaches
        $myCourseIds = \App\Models\LMS\CourseSession::where('instructor_user_id', $teacherId)
            ->distinct()
            ->pluck('course_id');

        $myStudents = \App\Models\SIS\Enrollment::active()
            ->whereIn('course_id', $myCourseIds)
            ->distinct('user_id')
            ->count('user_id');

        $ungradedSubmissions = \App\Models\LMS\AssessmentSubmission::whereIn('assessment_id', function($q) use ($teacherId) {
            $q->select('id')->from('assessments')->whereIn('course_id', function($subQ) use ($teacherId) {
                $subQ->select('course_id')->from('course_sessions')->where('instructor_user_id', $teacherId);
            });
        })->where('status', 'submitted')->count();

        return [
            Stat::make('Upcoming Classes', $myClasses)
                ->description('Sessions assigned to you')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->url(\App\Filament\Teacher\Resources\LMS\CourseSessions\CourseSessionResource::getUrl('index')),
                
            Stat::make('Total Students', $myStudents)
                ->description('Active students in your courses')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Ungraded Assessments', $ungradedSubmissions)
                ->description('Requires your attention')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color($ungradedSubmissions > 0 ? 'warning' : 'success')
                ->url(\App\Filament\Teacher\Resources\LMS\Assessments\AssessmentResource::getUrl('index')),
        ];
    }
}
