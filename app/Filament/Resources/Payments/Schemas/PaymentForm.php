<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Schedule;
use Filament\Support\RawJs;
use App\Enums\PaymentStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, Repeater, Textarea, TextInput, DatePicker};
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
                    ->label('Add Schedules')
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
                                } else {
                                    $set('amount', null);
                                }
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
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Schedule::query()
            ->with(['schedules.course', 'student']);
    }
}
