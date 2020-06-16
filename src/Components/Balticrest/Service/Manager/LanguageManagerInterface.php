<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

interface LanguageManagerInterface
{
    /** @var string */
    const CACHE_KEY = 'balticrest.language.active_languages';

    /** @var int Неделя */
    const CACHE_EXPIRE_TIME = 604800;

    /** @var string */
    const CACHE_TAG = 'languages';

    /**
     * @return array
     */
    public function getCachedActiveLanguages(): array;

    /**
     * @return array
     */
    public function getActiveLanguages(): array;
}