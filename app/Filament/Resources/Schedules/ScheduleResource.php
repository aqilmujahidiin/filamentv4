<?php

namespace App\Filament\Resources\Schedules;

use App\Enums\ScheduleStatusEnum;
use App\Filament\Resources\Schedules\Pages\ManageSchedules;
use App\Models\Course;
use App\Models\Schedule;
use BackedEnum;
use Filament\Actions\{BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\{DatePicker, Select, TimePicker};
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date'),
                TimePicker::make('start_time'),
                TimePicker::make('end_time'),
                Select::make('status')
                    ->options(ScheduleStatusEnum::class),
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->live()
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->relationship('course', 'name')
                    ->options(function (Get $get) {
                        $student = $get('student_id');
                        if (!$student) {
                            return [];
                        }

                        return Course::query()
                            ->whereHas('educationLevel', fn($query) => $query->where('id', $student->education_level_id))
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date('l, d M Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('hours')
                    ->label('Duration')
                    ->numeric()
                    ->suffix(' Hours')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('course.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teacher.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('calculated_fee')
                    ->label('Total Fee')
                    ->numeric()
                    ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.')),

                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updator.name')
                    ->label('Updated By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSchedules::route('/'),
        ];
    }
}
