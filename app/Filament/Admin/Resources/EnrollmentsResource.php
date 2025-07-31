<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EnrollmentsResource\Pages;
use App\Filament\Admin\Resources\EnrollmentsResource\RelationManagers;
use App\Models\Enrollments;
use App\Models\Student;
use App\Models\Courses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentsResource extends Resource
{
    protected static ?string $model = Enrollments::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Student Management';

    protected static ?string $navigationLabel = 'Enrollments';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Enrollment Details')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->required()
                            ->options(Student::all()->mapWithKeys(function ($student) {
                                return [$student->id => $student->first_name . ' ' . $student->last_name];
                            }))
                            ->searchable()
                            ->label('Student'),
                        Forms\Components\Select::make('course_id')
                            ->required()
                            ->options(Courses::all()->mapWithKeys(function ($course) {
                                return [$course->id => $course->course_name . ' (' . $course->course_code . ')'];
                            }))
                            ->searchable()
                            ->label('Course'),
                        Forms\Components\DatePicker::make('enrollment_date')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->label('Student')
                    ->getStateUsing(fn ($record) => $record->student->first_name . ' ' . $record->student->last_name)
                    ->searchable(['students.first_name', 'students.last_name']),
                Tables\Columns\TextColumn::make('course.course_name')
                    ->label('Course')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.course_code')
                    ->label('Course Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->options(Courses::all()->mapWithKeys(function ($course) {
                        return [$course->id => $course->course_name];
                    }))
                    ->label('Course'),
                Tables\Filters\Filter::make('enrolled_this_semester')
                    ->query(fn (Builder $query): Builder => $query->where('enrollment_date', '>=', now()->subMonths(4)))
                    ->label('Enrolled This Semester'),
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollments::route('/create'),
            'view' => Pages\ViewEnrollments::route('/{record}'),
            'edit' => Pages\EditEnrollments::route('/{record}/edit'),
        ];
    }
}
