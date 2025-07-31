<?php

namespace App\Filament\Widgets;

use App\Models\Grades;
use App\Models\Assignments;
use Filament\Widgets\ChartWidget;

class GradeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Grade Distribution';
    
    protected static ?int $sort = 3;

    // Make the pie chart take up half width to sit side by side with line chart
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    private function getLetterGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    protected function getData(): array
    {
        // Get all grades and calculate percentages
        $gradeLabels = ['A', 'B', 'C', 'D', 'F'];
        
        // Initialize grade counts
        $gradeCounts = array_fill_keys($gradeLabels, 0);
        
        // Get all grades with their assignments to calculate percentages
        $grades = Grades::with('assignment')->get();
        
        foreach ($grades as $grade) {
            if ($grade->assignment && $grade->assignment->max_score > 0) {
                $percentage = ($grade->score / $grade->assignment->max_score) * 100;
                $letterGrade = $this->getLetterGrade($percentage);
                $gradeCounts[$letterGrade]++;
            }
        }

        // Filter out grades with 0 count for cleaner chart
        $filteredCounts = [];
        $filteredLabels = [];
        
        foreach ($gradeCounts as $grade => $count) {
            if ($count > 0) {
                $filteredLabels[] = $grade;
                $filteredCounts[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $filteredCounts,
                    'backgroundColor' => [
                        '#10B981', // A - Green
                        '#3B82F6', // B - Blue
                        '#F59E0B', // C - Yellow
                        '#EF4444', // D - Red
                        '#7F1D1D', // F - Dark Red
                    ],
                    'borderWidth' => 1,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $filteredLabels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ": " + context.parsed + " (" + percentage + "%)";
                        }'
                    ]
                ]
            ],
            // Disable scales/grid lines for pie charts
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ]
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getHeight(): ?string
    {
        return '300px';
    }
}