<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables\Table;
use App\Enums\PaymentStatusEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\{Action, ActionGroup, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction, ViewAction};

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_amount')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->tooltip(fn(PaymentStatusEnum $state): string => $state->getLabel())
                    ->searchable(),
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
            ->defaultSort('created_at', 'desc')
            ->recordUrl(false)
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('bayar')
                    ->label('Bayar Sekarang')
                    ->url(fn($record) => route('payment.pay', $record))
                    ->openUrlInNewTab()
                    ->button()
                    ->icon('heroicon-o-credit-card')
                    ->visible(fn($record) => $record->payment_status === PaymentStatusEnum::Unpaid)
                    ->color(fn($record) => $record->payment_status === PaymentStatusEnum::Pending ? 'success' : 'warning'),

                ViewAction::make(),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
