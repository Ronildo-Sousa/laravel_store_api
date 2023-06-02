<?php

declare(strict_types = 1);

namespace App\Enums\Product;

enum StatusEnum: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
}
