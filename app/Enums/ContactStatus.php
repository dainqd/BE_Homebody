<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static APPROVED()
 * @method static static DELETED()
 * @method static static REJECTED()
 */
final class ContactStatus extends Enum
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const DELETED = 'DELETED';
    const REJECTED = 'REJECTED';
}
