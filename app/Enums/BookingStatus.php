<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingStatus extends Enum
{
    const PENDING = 'PENDING';
    const PROCESSING = 'PROCESSING';
    const CONFIRMED = 'CONFIRMED';
    const COMPLETED = 'COMPLETED';
    const CANCELED = 'CANCELED';
    const DELETED = 'DELETED';
}
