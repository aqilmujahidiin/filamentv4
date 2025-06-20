<?php

namespace App\Enums;

use Filament\Support\Contracts\{HasColor, HasLabel};

enum ScheduleStatusEnum: string implements HasLabel, HasColor
{
    case PENDING = 'On Progress';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
