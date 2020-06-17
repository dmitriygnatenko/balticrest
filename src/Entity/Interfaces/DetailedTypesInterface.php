<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface DetailedTypesInterface
{
    /** @var string */
    public const DETAILED_TYPE_BAR = 'cafes-bar';

    /** @var string */
    public const DETAILED_TYPE_PIZZA = 'cafes-pizza';

    /** @var string */
    public const DETAILED_TYPE_RESTAURANT = 'cafes-restaurant';

    /** @var string */
    public const DETAILED_TYPE_FREE_PARKING = 'transport-free-parking';

    /** @var string */
    public const DETAILED_TYPE_PARKING = 'transport-parking';
}