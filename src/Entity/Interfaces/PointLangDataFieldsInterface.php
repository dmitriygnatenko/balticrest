<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface PointLangDataFieldsInterface
{
    /** @var string */
    public const FIELD_ADDRESS = 'address';

    /** @var string */
    public const FIELD_DESC = 'desc';

    /** @var string */
    public const FIELD_SHORT_DESC = 'short_desc';
}