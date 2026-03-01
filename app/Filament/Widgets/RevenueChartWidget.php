<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SIS\Enrollment;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Revenue Trend';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $months = [];
        $revenue = [];

        // Simple mock for last 6 months or getting actual data
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            // Calculate actual revenue if it exists
            $total = Enrollment::whereYear('enrolled_at', $month->year)
                ->whereMonth('enrolled_at', $month->month)
                ->where('status', 'Enrolled')
                ->sum('amount_paid');
                
            $revenue[] = $total ?: 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue ($)',
                    'data' => $revenue,
                    'fill' => 'start',
                    'borderColor' => '#D4AF37', // warning / gold color
                    'backgroundColor' => 'rgba(212, 175, 55, 0.2)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
