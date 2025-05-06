<?php

namespace App\Enums;


enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case DECLINED = 'declined';

    public static function getStatuses(): array
    {
        return [
            self::PENDING->value,
            self::PROCESSING->value,
            self::COMPLETED->value,
            self::DECLINED->value,
        ];
    }
}
//
// Compare this snippet from database/migrations/2025_05_06_123113_create_categories_table.php: