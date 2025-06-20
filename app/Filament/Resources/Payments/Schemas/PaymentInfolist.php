<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use GrahamCampbell\ResultType\Success;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Info')
                    ->schema([
                        TextEntry::make('student.name')
                            ->label('Student Name')
                            ->icon('heroicon-s-user')
                            ->size('sm'),
                        TextEntry::make('total_amount')
                            ->prefix('Rp')
                            ->badge()
                            ->color('success')
                            ->numeric(),
                        TextEntry::make('payment_method')
                            ->badge(),
                        TextEntry::make('payment_date')
                            ->date(),
                        TextEntry::make('payment_status'),
                        TextEntry::make('created_at')
                            ->dateTime('d M Y H:i'),
                    ])->columns(3)
                    ->columnSpanFull()
            ]);
    }
}
