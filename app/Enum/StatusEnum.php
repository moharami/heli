<?php

namespace App\Enum;

enum StatusEnum
{
    const PENDING = 'pending';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';

    public static function toArray(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];
    }
}
