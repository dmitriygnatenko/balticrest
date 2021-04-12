<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface SearchDataObjectTypesInterface
{
    /** @var int */
    public const TYPE_POINT_ID = 1;

    /** @var int */
    public const TYPE_ARTICLE_ID = 2;
}