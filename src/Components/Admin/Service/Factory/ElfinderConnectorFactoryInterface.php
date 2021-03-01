<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Factory;

use elFinderConnector;

interface ElfinderConnectorFactoryInterface
{
    /**
     * @return elFinderConnector
     */
    public function create(): elFinderConnector;
}