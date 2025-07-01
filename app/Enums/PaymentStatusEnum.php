<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatusEnum: string implements HasColor, HasLabel, HasIcon
{
    case Pending = 'pending';
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Expired => 'danger',
            self::Cancelled => 'danger',
            self::Pending => 'warning',
            self::Unpaid => 'danger',
            self::Paid => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Expired => 'heroicon-o-x-circle',
            self::Cancelled => 'heroicon-o-x-circle',
            self::Pending => 'heroicon-o-clock',
            self::Unpaid => 'heroicon-o-x-circle',
            self::Paid => 'heroicon-o-check-circle',
        };
    }
}
