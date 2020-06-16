<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Manager\CityManager;
use App\Components\Balticrest\Service\Manager\CityManagerInterface;
use App\Components\Balticrest\Service\Manager\LanguageManager;
use App\Components\Balticrest\Service\Manager\LanguageManagerInterface;
use App\Components\Balticrest\Service\Manager\PointManager;
use App\Components\Balticrest\Service\Manager\PointManagerInterface;

class TwigGlobalService
{
    /** @var PointManager */
    private $pointManager;

    /** @var CityManager */
    private $cityManager;

    /** @var LanguageManager */
    private $languageManager;

    /**
     * @param PointManagerInterface $pointManager
     * @param CityManagerInterface $cityManager
     * @param LanguageManagerInterface $languageManager
     */
    public function __construct(
        PointManagerInterface $pointManager,
        CityManagerInterface $cityManager,
        LanguageManagerInterface $languageManager
    ) {
        $this->pointManager = $pointManager;
        $this->cityManager = $cityManager;
        $this->languageManager = $languageManager;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cityManager->getCachedActiveCities();
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languageManager->getCachedActiveLanguages();
    }

    /**
     * @return array
     */
    public function getPointTypes(): array
    {
        return $this->pointManager->getCachedPointTypes();
    }
}