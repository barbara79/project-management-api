<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case ON_HOLD = 'on_hold';

    public static function getAvailableStatuses(): array
    {
        return [
            self::TODO,
            self::IN_PROGRESS,
            self::DONE,
            self::ON_HOLD,
        ];
    }
}
