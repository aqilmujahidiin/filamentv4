<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StudentCoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $relatedResource = CourseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AttachAction::make()
                // ->form([
                //     Select::make('course_id')
                //         ->label('Course')
                //         ->relationship('courses', 'name')
                //         ->required(),
                // ]),
            ]);
    }
}
