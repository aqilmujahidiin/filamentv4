<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentStatusEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Info')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('student.name')
                                    ->label('Student Name')
                                    ->icon('heroicon-s-user')
                                    ->size('sm'),
                                TextEntry::make('student_guardian')
                                    ->label('Guardian  Name')
                                    ->icon('heroicon-s-user')
                                    ->size('sm'),
                            ])
                            ->columnSpanFull(),
                        Fieldset::make('Payment Info')
                            ->schema([
                                TextEntry::make('payment_amount')
                                    ->prefix('Rp')
                                    ->badge()
                                    ->color('success')
                                    ->numeric(),
                                TextEntry::make('va_number')
                                    ->label('VA Number')
                                    ->copyable(fn($state, $record) => $record->payment_status == PaymentStatusEnum::Pending)
                                    ->formatStateUsing(fn($state, $record) => $record->payment_status == PaymentStatusEnum::Expired
                                        ? 'Expired'
                                        : $state)
                                    ->icon('heroicon-s-clipboard'),
                                TextEntry::make('payment_method')
                                    ->badge(),
                                TextEntry::make('bank')
                                    ->label('Bank'),
                                TextEntry::make('payment_status')
                                    ->badge(),
                                TextEntry::make('midtrans_transaction_id')
                                    ->label('Transaction ID'),
                                TextEntry::make('payment_date')
                                    ->date('d M Y'),
                                TextEntry::make('expiry_time')
                                    ->label('Expiry Time')
                                    ->dateTime('d M Y H:i'),

                                TextEntry::make('payment_note')
                                    ->label('Note'),
                            ])->columnSpanFull(),
                    ])->columns(3)
                    ->columnSpanFull()
            ]);
    }
}
