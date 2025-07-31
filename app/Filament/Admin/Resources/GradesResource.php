<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GradesResource\Pages;
use App\Filament\Admin\Resources\GradesResource\RelationManagers;
use App\Models\Grades;
use App\Models\Enrollments;
use App\Models\Assignments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Courses;

class GradesResource extends Resource
{
    protected static ?string $model = Grades::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Grade Management';

    protected static ?string $navigationLabel = 'Grades';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Grade Assignment')
                    ->schema([
                        Forms\Components\Select::make('enrollment_id')
                            ->required()
                            ->options(Enrollments::with(['student', 'course'])->get()->mapWithKeys(function ($enrollment) {
                                return [$enrollment->id => $enrollment->student->first_name . ' ' . $enrollment->student->last_name . ' - ' . $enrollment->course->course_name];
                            }))
                            ->searchable()
                            ->label('Student Enrollment'),
                        Forms\Components\Select::make('assignment_id')
                            ->required()
                            ->options(Assignments::with('course')->get()->mapWithKeys(function ($assignment) {
                                return [$assignment->id => $assignment->name . ' (' . $assignment->course->course_name . ')'];
                            }))
                            ->searchable()
                            ->label('Assignment'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Grade Details')
                    ->schema([
                        Forms\Components\TextInput::make('score')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        Forms\Components\Select::make('letter_grade')
                            ->options([
                                'A+' => 'A+',
                                'A' => 'A',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B' => 'B',
                                'B-' => 'B-',
                                'C+' => 'C+',
                                'C' => 'C',
                                'C-' => 'C-',
                                'D+' => 'D+',
                                'D' => 'D',
                                'D-' => 'D-',
                                'F' => 'F',
                            ])
                            ->searchable()
                            ->nullable(),
                        Forms\Components\DatePicker::make('graded_on')
                            ->required()
                            ->default(now()),
                        Forms\Components\Textarea::make('comments')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->label('Student')
                    ->getStateUsing(fn ($record) => $record->enrollment->student->first_name . ' ' . $record->enrollment->student->last_name)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('assignment.course.course_name')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignment.name')
                    ->label('Assignment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('letter_grade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A+', 'A', 'A-' => 'success',
                        'B+', 'B', 'B-' => 'info',
                        'C+', 'C', 'C-' => 'warning',
                        'D+', 'D', 'D-', 'F' => 'danger',
                        default => 'gray',
                    })->sortable(),
                Tables\Columns\TextColumn::make('graded_on')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('assignment.course_id')
                    ->relationship('assignment.course', 'course_name')
                    ->label('Course')
                    ->placeholder('Filter by course'),
                Tables\Filters\SelectFilter::make('letter_grade')
                    ->options([
                        'A+' => 'A+', 'A' => 'A', 'A-' => 'A-',
                        'B+' => 'B+', 'B' => 'B', 'B-' => 'B-',
                        'C+' => 'C+', 'C' => 'C', 'C-' => 'C-',
                        'D+' => 'D+', 'D' => 'D', 'D-' => 'D-',
                        'F' => 'F',
                    ])
                    ->label('Letter Grade'),
                Tables\Filters\Filter::make('recent_grades')
                    ->query(fn (Builder $query): Builder => $query->where('graded_on', '>=', now()->subWeek()))
                    ->label('Graded This Week'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrades::route('/create'),
            'view' => Pages\ViewGrades::route('/{record}'),
            'edit' => Pages\EditGrades::route('/{record}/edit'),
        ];
    }
}
