<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use Symfony\Component\Form\AbstractType;

abstract class AbstractForm extends AbstractType
{
    /** @var string */
    public const VALIDATION_GROUP_CREATE = 'create';

    /** @var string */
    public const VALIDATION_GROUP_UPDATE = 'update';

    /** @var string[] */
    public const ALL_VALIDATION_GROUPS = [
        self::VALIDATION_GROUP_CREATE,
        self::VALIDATION_GROUP_UPDATE
    ];

    /** @var array */
    public const REQUIRED_LANGUAGES = ['ru', 'en'];
}