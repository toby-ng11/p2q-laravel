<?php

namespace App\Enums;

enum UserRole: int
{
    case GUEST = 1;
    case SALES = 2;
    case ARCHREP = 3;
    case MANAGER = 4;
    case ADMIN = 5;

    public function label(): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }

    public function level(): int
    {
        return $this->value;
    }

    public function atLeast(self $role): bool
    {
        return $this->level() >= $role->level();
    }
}
