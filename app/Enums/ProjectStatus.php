<?php

namespace App\Enums;

enum ProjectStatus: int
{
    case PRE_DESIGN = 1;
    case SCHEMATIC_DESIGN = 2;
    case DESIGN_DEVELOPMENT = 3;
    case BUILDING_PERMITTING = 4;
    case CONSTRUCTION_DOCUMENTS = 5;
    case BIDDING = 6;
    case CONSTRUCTION_ADMINISTRATION = 7;
    case WON = 8;
    case CLOSED = 9;
    case UNKNOWN = 10;

    case DRAFT = 11;

    case IN_REVIEW = 12;
    case REJECTED = 13;
    case APPROVED = 14;

    public function label(): string
    {
        if ($this === self::PRE_DESIGN) {
            return 'Pre-Design';
        }

        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }
}
