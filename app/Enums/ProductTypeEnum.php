<?php

namespace App\Enums;


enum ProductTypeEnum: string
{
    case DELIVERABLE = 'deliverable';
    case DOWNLOADABLE = 'downloadable';

    public static function getTypes(): array
    {
        return [
            self::DELIVERABLE->value,
            self::DOWNLOADABLE->value,
        ];
    }
}

// Compare this snippet from database/migrations/2025_05_06_123113_create_categories_table.php: