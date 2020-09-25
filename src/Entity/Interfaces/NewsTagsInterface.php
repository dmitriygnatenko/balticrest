<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface NewsTagsInterface
{
    /** @var string */
    public const TAG_TRANSPORT = 'transport';

    /** @var string */
    public const TAG_OFFERS = 'offers';

    /** @var string */
    public const TAG_EVENTS = 'events';
}