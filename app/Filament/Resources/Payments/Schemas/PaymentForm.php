<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Schedule;
use Filament\Support\RawJs;
use App\Enums\PaymentStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'virtual_account' => 'Virtual Account',
                    ]),
                DatePicker::make('payment_date'),
                ToggleButtons::make('payment_status')
                    ->inline()
                    ->options(PaymentStatus::class),

                Textarea::make('payment_note')
                    ->columnSpanFull(),

                Repeater::make('schedulePayments')
                    ->label('Schedules')
                    ->relationship()
                    ->schema([
                        Select::make('schedule_id')
                            ->relationship('schedule', 'id', fn($query) => $query->completed())
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                $record->course->name . ' - ' . $record->date->format('d/m/Y') .
                                ' (Rp ' . number_format($record->calculated_fee, 0, ',', '.') . ')'
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state) {
                                    $schedule = Schedule::find($state);
                                    if ($schedule) {
                                        $set('amount', $schedule->calculateFee());
                                    }
                                }

                                // Update total menggunakan helper
                                $set('../../total_amount', self::calculateTotal($get('../../')));
                            }),

                        TextInput::make('amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->live(),

                    ])
                    ->live()
                    ->columns(2)
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Calculate total from all schedule payments
                        $total = collect($state ?? [])
                            ->sum(function ($item) {
                            return is_numeric($item['amount']) ? $item['amount'] : 0;
                        });

                        $set('total_amount', $total);
                    }),
                TextInput::make('payment_amount')
                    ->prefix('Rp')
                    // ->mask(RawJs::make('$money($input)'))
                    // ->stripCharacters(',')
                    ->numeric()
                    ->required()
                    ->live(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Schedule::query()
            ->with(['schedules.course', 'student']);
    }

    private static function calculateTotal($get): float
    {
        $schedulePayments = $get('schedulePayments') ?? [];
        return collect($schedulePayments)
            ->sum(fn($payment) => is_numeric($payment['amount']) ? $payment['amount'] : 0);
    }
}
