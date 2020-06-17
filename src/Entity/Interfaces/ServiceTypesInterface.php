<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface ServiceTypesInterface
{
    /** @var string */
    public const FIELD_SERVICE_PARKING = 'parking';

    /** @var string */
    public const FIELD_SERVICE_RESTAURANT = 'restaurant';

    /** @var string */
    public const FIELD_SERVICE_CONFERENCE_HALL = 'conference-hall';

    /** @var string */
    public const FIELD_SERVICE_SPA = 'spa';

    /** @var string */
    public const FIELD_SERVICE_FITNESS = 'fitness';

    /** @var string */
    public const FIELD_SERVICE_BAR = 'bar';

    /** @var string */
    public const FIELD_SERVICE_INDOOR_POOL = 'indoor-pool';

    /** @var string */
    public const FIELD_SERVICE_OUTDOOR_POOL = 'outdoor-pool';

    /** @var string */
    public const FIELD_SERVICE_WI_FI = 'wi-fi';

    /** @var string */
    public const FIELD_SERVICE_CAR_RENTAL = 'car-rental';

    /** @var string */
    public const FIELD_SERVICE_SAFE = 'safe';

    /** @var string */
    public const FIELD_SERVICE_TRANSFER = 'transfer';

    /** @var string */
    public const FIELD_SERVICE_SAUNA = 'sauna';

    /** @var string */
    public const FIELD_SERVICE_LIBRARY = 'library';

    /** @var string */
    public const FIELD_SERVICE_LAUNDRY = 'laundry';

    /** @var string */
    public const FIELD_SERVICE_CHILDREN_PLAYGROUND = 'children-playground';

    /** @var string */
    public const FIELD_SERVICE_GUIDE = 'guide';
}