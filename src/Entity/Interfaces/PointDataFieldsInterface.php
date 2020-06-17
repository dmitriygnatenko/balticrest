<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface PointDataFieldsInterface
{
    /** @var string */
    public const FIELD_EMAIl = 'email';

    /** @var string */
    public const FIELD_WEBSITE = 'website';

    /** @var string */
    public const FIELD_PHONES = 'phones';

    /** @var string */
    public const FIELD_COMMENT = 'comment';

    /** @var string */
    public const FIELD_SERVICES = 'services';

    /** @var string */
    public const FIELD_DETAILED_TYPE = 'detailed-type';
}