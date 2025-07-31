<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AssignmentsResource\Pages;
use App\Filament\Admin\Resources\AssignmentsResource\RelationManagers;
use App\Models\Assignments;
use App\Models\Courses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssignmentsResource extends Resource
{
    protected static ?string $model = Assignments::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?string $navigationLabel = 'Assignments';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assignment Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('course_id')
                            ->required()
                            ->options(Courses::all()->mapWithKeys(function ($course) {
                                return [$course->id => $course->course_name . ' (' . $course->course_code . ')'];
                            }))
                            ->searchable()
                            ->label('Course'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Assignment Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->collapsible()
                    ->collapsed(),
                
                Forms\Components\Section::make('Assignment Settings')
                    ->schema([
                        Forms\Components\DatePicker::make('due_date')
                            ->required(),
                        Forms\Components\TextInput::make('max_score')
                            ->required()
                            ->numeric()
                            ->default(100)
                            ->minValue(1),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.course_name')
                    ->label('Course')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.course_code')
                    ->label('Course Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_score')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->options(Courses::all()->mapWithKeys(function ($course) {
                        return [$course->id => $course->course_name];
                    }))
                    ->label('Course'),
                Tables\Filters\Filter::make('due_soon')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<=', now()->addWeek()))
                    ->label('Due This Week'),
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
            RelationManagers\GradesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignments::route('/create'),
            'view' => Pages\ViewAssignments::route('/{record}'),
            'edit' => Pages\EditAssignments::route('/{record}/edit'),
        ];
    }


}
