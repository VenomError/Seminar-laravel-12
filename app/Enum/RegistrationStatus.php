<?php

namespace App\Enum;

enum RegistrationStatus: string
{
    case PENDING = "pending";
    case CONFIRMED = "confirmed";
    case REJECTED = "rejected";
    case CANCELED = "canceled";


    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::CANCELED => 'danger',
            self::REJECTED => 'danger',
        };
    }
}
