<?php

namespace App\Enum;

enum SeminarStatus: string
{
    case DRAFT = "draft";
    case PUBLISHED = "published";
    case CLOSED = "closed";


    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function color()
    {
        return match ($this) {
            self::DRAFT => 'primary',
            self::PUBLISHED => 'success',
            self::CLOSED => 'danger',
        };
    }

}
