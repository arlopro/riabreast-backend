<?php

namespace App\Enums;

enum RoleEnum: int
{
    case DEV = 1;
    case ADMIN = 2;
    case USER = 3;

    public function description(): string
    {
        return match($this) {
            self::DEV => 'Sviluppatore',
            self::ADMIN => 'Amministratore',
            self::USER => 'Utente',
        };
    }

    public function isGreaterOrEqualTo(self $role): bool
    {
        return $this->value <= $role->value || $this === self::DEV;
    }
}
