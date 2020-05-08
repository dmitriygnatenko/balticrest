<?php

namespace App\Components\Balticrest\Service\Manager;

interface LanguageManagerInterface
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