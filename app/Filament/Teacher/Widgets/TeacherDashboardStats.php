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
            
        // Calculate total students assigned safely through session courses
        $myStudents = \App\Models\SIS\CourseAccess::whereIn('course_id', function($q) use ($teacherId) {
            $q->select('course_id')->from('course_sessions')->where('instructor_user_id', $teacherId);
        })->where('is_active', true)->distinct('enrollment_id')->count();

        $ungradedSubmissions = \App\Models\LMS\AssessmentSubmission::whereIn('assessment_id', function($q) use ($teacherId) {
            $q->select('id')->from('assessments')->whereIn('course_id', function($subQ) use ($teacherId) {
                $subQ->select('course_id')->from('course_sessions')->where('instructor_user_id', $teacherId);
            });
        })->where('status', 'submitted')->count();

        return [
            Stat::make('Upcoming Classes', $myClasses)
                ->description('Sessions assigned to you')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
                
            Stat::make('Total Students', $myStudents)
                ->description('Active students in your courses')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Ungraded Assessments', $ungradedSubmissions)
                ->description('Requires your attention')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color($ungradedSubmissions > 0 ? 'warning' : 'success'),
        ];
    }
}
