<?php

namespace App\Components\Balticrest\Service\Manager;

interface CityManagerInterface
{
    /**
     * @return array
     */
    public function getActiveCached(): array;

    /**
     * @return array
     */
    public function getActive(): array;
}