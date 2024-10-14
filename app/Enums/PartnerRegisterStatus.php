<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PartnerRegisterStatus extends Enum
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';
    const DELETED = 'DELETED';
}
