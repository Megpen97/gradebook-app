<?php

namespace App\Filament\Admin\Resources\AssignmentsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Student;
use App\Models\Enrollments;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Hidden field for assignment_id (automatically set)
                Forms\Components\Hidden::make('assignment_id')
                    ->default(fn () => $this->getOwnerRecord()->id),
                
                // Display the assignment name (read-only)
                Forms\Components\TextInput::make('assignment_name')
                    ->label('Assignment')
                    ->default(fn () => $this->getOwnerRecord()->name)
                    ->disabled()
                    ->dehydrated(false),
                
                // SELECT ENROLLMENT - Only students enrolled in this course
                Forms\Components\Select::make('enrollment_id')
                    ->label('Student')
                    ->required()
                    ->options(function () {
                        $courseId = $this->getOwnerRecord()->course_id;
                        return Enrollments::with('student')
                            ->where('course_id', $courseId)
                            ->get()
                            ->mapWithKeys(function ($enrollment) {
                                return [
                                    $enrollment->id => $enrollment->student->first_name . ' ' . $enrollment->student->last_name
                                ];
                            });
                    })
                    ->searchable()
                    ->preload(),
                
                // Score field with auto letter grade calculation
                Forms\Components\TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $maxScore = $this->getOwnerRecord()->max_score;
                        if ($state && $maxScore > 0) {
                            $percentage = ($state / $maxScore) * 100;
                            $letterGrade = $this->calculateLetterGrade($percentage);
                            $set('letter_grade', $letterGrade);
                        }
                    }),
                
                // Letter grade field (auto-populated)
                Forms\Components\Select::make('letter_grade')
                    ->options([
                        'A+' => 'A+', 'A' => 'A', 'A-' => 'A-',
                        'B+' => 'B+', 'B' => 'B', 'B-' => 'B-',
                        'C+' => 'C+', 'C' => 'C', 'C-' => 'C-',
                        'D+' => 'D+', 'D' => 'D', 'D-' => 'D-',
                        'F' => 'F',
                    ])
                    ->nullable()
                    ->helperText('Auto-calculated based on score percentage'),
                
                // Graded on date
                Forms\Components\DatePicker::make('graded_on')
                    ->required()
                    ->default(now()),
                
                // Comments
                Forms\Components\Textarea::make('comments')
                    ->maxLength(65535),
            ]);
    }

    private function calculateLetterGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 97 => 'A+',
            $percentage >= 93 => 'A',
            $percentage >= 90 => 'A-',
            $percentage >= 87 => 'B+',
            $percentage >= 83 => 'B',
            $percentage >= 80 => 'B-',
            $percentage >= 77 => 'C+',
            $percentage >= 73 => 'C',
            $percentage >= 70 => 'C-',
            $percentage >= 67 => 'D+',
            $percentage >= 63 => 'D',
            $percentage >= 60 => 'D-',
            default => 'F',
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('score')
            ->columns([
                Tables\Columns\TextColumn::make('enrollment.student.first_name')
                    ->label('Student')
                    ->formatStateUsing(fn ($record) => $record->enrollment->student->first_name . ' ' . $record->enrollment->student->last_name),
                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('letter_grade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A+', 'A', 'A-' => 'success',
                        'B+', 'B', 'B-' => 'info',
                        'C+', 'C', 'C-' => 'warning',
                        'D+', 'D', 'D-', 'F' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('graded_on')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('letter_grade')
                    ->options([
                        'A+' => 'A+', 'A' => 'A', 'A-' => 'A-',
                        'B+' => 'B+', 'B' => 'B', 'B-' => 'B-',
                        'C+' => 'C+', 'C' => 'C', 'C-' => 'C-',
                        'D+' => 'D+', 'D' => 'D', 'D-' => 'D-',
                        'F' => 'F',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
