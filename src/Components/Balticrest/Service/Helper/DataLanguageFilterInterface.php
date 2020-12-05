<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use Doctrine\Common\Collections\Collection;

interface DataLanguageFilterInterface
{
    /**
     * @param Collection $collection
     * @param string $locale
     *
     * @return object|null
     */
    public function filter(Collection $collection, string $locale): ?object;
}