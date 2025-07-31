<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CoursesResource\Pages;
use App\Filament\Admin\Resources\CoursesResource\RelationManagers;
use App\Models\Courses;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\Widget;


class CoursesResource extends Resource
{
    protected static ?string $model = Courses::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?string $navigationLabel = 'Courses';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'course_name';

    public static function getRecordTitle(?Model $record): string|null
    {
        return $record?->course_name . ' ' . $record?->course_code;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('course_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('instructor_id')
                    ->required()
                    ->options(Instructor::all()->mapWithKeys(function ($instructor) {
                        return [$instructor->id => $instructor->first_name . ' ' . $instructor->last_name];
                    }))
                    ->searchable()
                    ->label('Instructor'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructor_name')
                    ->label('Instructor')
                    ->getStateUsing(fn ($record) => $record->instructor->first_name . ' ' . $record->instructor->last_name),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('instructor_id')
                    ->options(Instructor::all()->mapWithKeys(function ($instructor) {
                        return [$instructor->id => $instructor->first_name . ' ' . $instructor->last_name];
                    }))
                    ->label('Instructor'),
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
            RelationManagers\EnrollmentsRelationManager::class,
            RelationManagers\AssignmentsRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Course Details')
                    ->schema([
                        TextEntry::make('course_name'),
                        TextEntry::make('course_code'),
                        TextEntry::make('instructor_name')
                            ->getStateUsing(fn ($record) => $record->instructor->first_name . ' ' . $record->instructor->last_name),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourses::route('/create'),
            'view' => Pages\ViewCourses::route('/{record}'),
            'edit' => Pages\EditCourses::route('/{record}/edit'),
        ];
    }

}
