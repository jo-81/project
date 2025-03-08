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
}
