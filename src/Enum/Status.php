<?php

namespace App\Enum;

enum Status: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function getIcon(): string
    {
        return match ($this) {
            self::TODO => 'tabler:antenna-bars-1',
            self::IN_PROGRESS => 'tabler:antenna-bars-3',
            self::DONE => 'tabler:antenna-bars-5',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::TODO => 'danger',
            self::IN_PROGRESS => 'warning',
            self::DONE => 'success',
        };
    }
}
