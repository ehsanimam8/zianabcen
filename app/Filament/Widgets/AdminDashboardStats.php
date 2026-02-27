<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalStudents = \App\Models\User::role(['Student', 'student'])->count();
        $activeClasses = \App\Models\SIS\Course::where('is_active', true)->count();
        $totalPayments = \App\Models\SIS\Enrollment::sum('amount_paid') + \App\Models\CRM\Donation::where('status', 'completed')->sum('amount') + \App\Models\CRM\Sponsorship::where('status', 'active')->sum('amount');
        
        $unpaidStudents = \App\Models\SIS\Enrollment::where('payment_method', 'manual')
            ->whereNull('stripe_payment_intent_id')
            ->where(function($q) {
                $q->whereNull('amount_paid')->orWhere('amount_paid', 0);
            })->count();

        return [
            Stat::make('Total Paid Active Students', $totalStudents)
                ->description('Registered student accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('Active Courses', $activeClasses)
                ->description('Courses currently active')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Pending Payments (Manual)', $unpaidStudents)
                ->description('Enrollments awaiting payment')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($unpaidStudents > 0 ? 'danger' : 'success'),
                
            Stat::make('Total Revenue Collected', '$' . number_format($totalPayments, 2))
                ->description('Overall revenue (Courses + Donations + Sponsorships)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
