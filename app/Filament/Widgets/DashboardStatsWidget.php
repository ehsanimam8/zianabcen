<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\SIS\Enrollment;
use Illuminate\Support\Facades\DB;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $tenantId = tenant('id');
        
        // Count students
        $totalStudents = User::role('Student')->count();
        
        // Count active enrollments for current term or overall if no current term
        $currentTerm = \App\Models\SIS\Term::where('is_current', true)->first();
        $enrollmentQuery = Enrollment::where('status', 'Enrolled');
        if ($currentTerm) {
            $enrollmentQuery->where('term_id', $currentTerm->id);
        }
        $activeEnrollments = $enrollmentQuery->count();

        // Calculate total revenue from enrollments directly or CRM payments
        // We'll use amount_paid on Enrollment for simplicity here
        $totalRevenue = Enrollment::sum('amount_paid') ?? 0;

        return [
            Stat::make('Total Registered Students', $totalStudents)
                ->description('Active learning accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 8, 9, 12]),
                
            Stat::make('Active Enrollments', $activeEnrollments)
                ->description($currentTerm ? 'In Current Term: ' . $currentTerm->name : 'Total Enrolled')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->chart([4, 6, 8, 10, 15, 20, 25]),
                
            Stat::make('Overall Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total course & program fees')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([100, 200, 300, 450, 600, 800, 1000]),
        ];
    }
}
