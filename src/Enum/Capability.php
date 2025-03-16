<?php

namespace App\Enum;

enum Capability: string
{
    case VIP = 'vip';
    case PREMIUM = 'premium';
    case VISITOR = 'visitor';

    public function getProjectLimit(): int
    {
        return match ($this) {
            self::VIP => -1,
            self::PREMIUM => 10,
            self::VISITOR => 5,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::VIP => 'tabler:vip',
            self::PREMIUM => 'tabler:currency-dollar',
            self::VISITOR => 'tabler:vip-off',
        };
    }
}
