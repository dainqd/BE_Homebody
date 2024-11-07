<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MyCouponStatus extends Enum
{
    const USED = "USED";
    const UNUSED = "UNUSED";
    const EXPIRED = "EXPIRED";
}
