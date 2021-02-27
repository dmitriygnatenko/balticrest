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

    /** @var array */
    public const REQUIRED_LANGUAGES = ['ru', 'en'];
}