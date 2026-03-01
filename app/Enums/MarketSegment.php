<?php

namespace App\Enums;

enum MarketSegment: int
{
    case INDUSTRIAL = 1;
    case COMMERCIAL = 2;
    case RETAIL = 3;
    case RESIDENTIAL = 4;
    case HEALTHCARE = 5;
    case HOSPITALITY = 6;
    case EDUCATION = 7;
    case GOVERNMENT = 8;
    case INFRASTRUCTURE = 9;

    public function label(): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }
}
