<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = "pending";
    case VERIFIED = "verified";
    case FAILED = "failed";


    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::VERIFIED => 'success',
            self::FAILED => 'danger',
        };
    }
}
