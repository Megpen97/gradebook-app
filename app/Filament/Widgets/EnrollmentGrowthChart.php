<?php

namespace App\Filament\Widgets;

use App\Models\Enrollments;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class EnrollmentGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Enrollment Growth Over Time';
    
    protected static ?int $sort = 2;

    // Control the width of the widget - half width to sit side by side with pie chart
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        // Get enrollment data for the last 12 months
        $enrollmentData = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $labels[] = $monthLabel;
            
            // Count enrollments up to this month (cumulative)
            $count = Enrollments::where('enrollment_date', '<=', $month->endOfMonth())->count();
            $enrollmentData[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Enrollments',
                    'data' => $enrollmentData,
                    'borderColor' => 'rgb(59, 130, 246)', // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 150, // Set maximum to 150
                    'title' => [
                        'display' => true,
                        'text' => 'Number of Enrollments',
                    ],
                    'ticks' => [
                        'stepSize' => 50, // Increments of 50
                        'precision' => 0, // No decimal places
                    ],
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.1)', // Light grid lines
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Month',
                    ],
                    'grid' => [
                        'display' => false, // Hide vertical grid lines for cleaner look
                    ]
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => true,
        ];
    }

    // Make the chart taller
    protected function getHeight(): ?string
    {
        return '450px'; // Much taller height for better visibility
    }
}