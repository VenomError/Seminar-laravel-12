<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PARTICIPANT = 'participant';

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::PARTICIPANT => 'Participant',
        };
    }

    public function pathRedirect()
    {
        return match ($this) {
            self::ADMIN => '/dashboard',
            self::PARTICIPANT => '/',
        };
    }
    public function sidenavConfig()
    {
        return match ($this) {
            self::ADMIN => 'sidenav.admin',
            self::PARTICIPANT => 'sidenav.participant',
        };
    }
    public function color()
    {
        return match ($this) {
            self::ADMIN => 'primary',
            self::PARTICIPANT => 'success',
        };
    }
}
