<?php

namespace App\Filament\Resources\Teachers;

use App\Enums\TeacherLastEducationEnum;
use App\Filament\Resources\Teachers\Pages\ManageTeachers;
use App\Models\Teacher;
use BackedEnum;
use UnitEnum;
use Filament\Actions\{BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, Select, Textarea, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;
    protected static string|UnitEnum|null $navigationGroup = 'Users';


    public static function form(Schema $schema): Schema
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
                    ])->columnSpan(2),
                Select::make('last_education')
                    ->options(TeacherLastEducationEnum::class),
                TextInput::make('major'),
                DatePicker::make('birth_date'),
                TextInput::make('payment_account_name'),
                TextInput::make('payment_account_number')
                    ->numeric(),
                TextInput::make('payment_bank_name'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable(),
                TextColumn::make('last_education')
                    ->badge()
                    ->searchable(),
                TextColumn::make('major')
                    ->searchable(),
                TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User ID')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->label('Updated By')
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
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ManageTeachers::route('/'),
        ];
    }
}
