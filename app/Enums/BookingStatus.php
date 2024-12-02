<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingStatus extends Enum
{
    const PENDING = 'PENDING';
    const PROCESSING = 'PROCESSING';
    const COMPLETED = 'COMPLETED';
    const REJECTED = 'REJECTED';
    const CANCELED = 'CANCELED';
    const DELETED = 'DELETED';
}
