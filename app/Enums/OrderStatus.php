<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    const PROCESSING = 'PROCESSING';
    const WAIT_PAYMENT = 'WAIT_PAYMENT';
    const SHIPPING = 'SHIPPING';
    const DELIVERED = 'DELIVERED';
    const COMPLETED = 'COMPLETED';
    const CANCELED = 'CANCELED';
    const DELETED = 'DELETED';
}
