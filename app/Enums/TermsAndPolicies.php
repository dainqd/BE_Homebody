<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TERM()
 * @method static static POLICY()
 * @method static static OTHER()
 */
final class TermsAndPolicies extends Enum
{
    const TERM = 'TERM';
    const POLICY = 'POLICY';
    const OTHER = 'OTHER';
}
