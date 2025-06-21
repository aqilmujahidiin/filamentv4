<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->tooltip(fn(PaymentStatus $state): string => $state->getLabel())
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
            ->recordUrl(false)
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('bayar')
                    ->label(fn($record) => $record->payment_status === PaymentStatus::Pending ? 'Lanjutkan Pembayaran' : 'Bayar Sekarang')
                    ->url(fn($record) => route('payment.pay', $record))
                    ->openUrlInNewTab()
                    ->button()
                    ->icon('heroicon-o-credit-card')
                    ->visible(fn($record) => in_array($record->payment_status, [PaymentStatus::Unpaid, PaymentStatus::Pending]))
                    ->color(fn($record) => $record->payment_status === PaymentStatus::Pending ? 'success' : 'warning'),

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
