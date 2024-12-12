<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingType extends Enum
{
    const PAID = 'PAID';
    const FREE = 'FREE';
    const UNPAID = 'UNPAID';
}
