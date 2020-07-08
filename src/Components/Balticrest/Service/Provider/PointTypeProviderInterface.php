<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface PointTypeProviderInterface
{
    /**
     * @return array
     */
    public function getCachedPointTypes(): array;

    /**
     * @return array
     */
    public function getPointTypes(): array;
}