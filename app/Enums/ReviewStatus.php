<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReviewStatus extends Enum
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const DELETED = 'DELETED';
    const REJECTED = 'REJECTED';
}
