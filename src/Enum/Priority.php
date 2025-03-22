<?php

namespace App\Enum;

enum Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGHT = 'hight';

    public function getColor(): string
    {
        return match ($this) {
            self::LOW => 'info',
            self::MEDIUM => 'warning',
            self::HIGHT => 'danger',
        };
    }

    public function getFirstLetter()
    {
        return match ($this) {
            self::LOW => 'L',
            self::MEDIUM => 'M',
            self::HIGHT => 'H',
        };
    }
}
