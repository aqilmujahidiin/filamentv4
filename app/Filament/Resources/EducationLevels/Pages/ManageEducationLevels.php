<?php

namespace App\Filament\Resources\EducationLevels\Pages;

use App\Filament\Resources\EducationLevels\EducationLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationLevels extends ManageRecords
{
    protected static string $resource = EducationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
