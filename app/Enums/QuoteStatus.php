<?php

namespace App\Enums;

enum QuoteStatus: int
{
    case DRAFT = 1;
    case IN_REVIEW = 2;
    case REJECTED = 3;
    case APPROVED = 4;

    public function label(): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }
}
