<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('email')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('address')
                    ->columnSpanFull(),
                Grid::make(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]),
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ]),
                    ]),

                DatePicker::make('birth_date'),
                Select::make('education_level_id')
                    ->label('Education Level')
                    ->relationship('educationLevel', 'name'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('guardian_id')
                    ->relationship('guardian', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
