<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Manager\CityManager;
use App\Components\Balticrest\Service\Manager\CityManagerInterface;
use App\Components\Balticrest\Service\Manager\LanguageManager;
use App\Components\Balticrest\Service\Manager\LanguageManagerInterface;

class TwigGlobalService
{
    /** @var CityManager */
    private $cityManager;

    /** @var LanguageManager */
    private $languageManager;

    /**
     * @param CityManagerInterface $cityManager
     * @param LanguageManagerInterface $languageManager
     */
    public function __construct(CityManagerInterface $cityManager, LanguageManagerInterface $languageManager)
    {
        $this->cityManager = $cityManager;
        $this->languageManager = $languageManager;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cityManager->getActiveCached();
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languageManager->getActiveCached();
    }
}