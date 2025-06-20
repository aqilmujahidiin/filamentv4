<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\{CreatePayment, EditPayment, ListPayments, ViewPayment};
use App\Filament\Resources\Payments\Schemas\{PaymentForm, PaymentInfolist};
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Filament\Resources\Schedules\RelationManagers\SchedulesRelationManager;
use App\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view' => ViewPayment::route('/{record}'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
