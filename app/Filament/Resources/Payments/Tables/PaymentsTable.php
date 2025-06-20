<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_amount')
                    ->numeric()
                    ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                TagsColumn::make('paid_schedule_list')
                    ->label('Paid Schedule')
                    // ->getStateUsing(function ($record) {
                    //     return $record->schedules()
                    //         ->with('course')
                    //         ->get()
                    //         ->map(function ($schedule) {
                    //             return collect([
                    //                 'course' => $schedule->course->name,
                    //                 'date' => $schedule->date->format('d/m/Y')
                    //             ])->implode(' (') . ')';
                    //         })
                    //         ->join(', ');
                    // })
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList(2)
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('schedules.course', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->label('Updated By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
