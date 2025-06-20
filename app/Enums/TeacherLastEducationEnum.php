<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TeacherLastEducationEnum: string implements HasColor, HasLabel
{
    case D4 = 'D4';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::D4 => 'warning',
            self::S1 => 'success',
            self::S2 => 'info',
            self::S3 => 'danger',
        };
    }
}
