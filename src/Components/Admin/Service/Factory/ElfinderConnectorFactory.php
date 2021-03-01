<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Factory;

use elFinderConnector;

class ElfinderConnectorFactory implements ElfinderConnectorFactoryInterface
{
    /** @var ElfinderFactoryInterface */
    private $elfinderFactory;

    /**
     * @param ElfinderFactoryInterface $elfinderFactory
     */
    public function __construct(ElfinderFactoryInterface $elfinderFactory)
    {
        $this->elfinderFactory = $elfinderFactory;
    }

    /**
     * @return elFinderConnector
     */
    public function create(): elFinderConnector
    {
        return new elFinderConnector($this->elfinderFactory->create());
    }
}