<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasColor, HasLabel, HasIcon
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';

    public function getLabel(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Unpaid => 'danger',
            self::Paid => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Unpaid => 'heroicon-o-x-circle',
            self::Paid => 'heroicon-o-check-circle',
        };
    }
}
