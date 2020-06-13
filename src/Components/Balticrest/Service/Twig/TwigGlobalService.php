<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Manager\CityManager;
use App\Components\Balticrest\Service\Manager\CityManagerInterface;
use App\Components\Balticrest\Service\Manager\LanguageManager;
use App\Components\Balticrest\Service\Manager\LanguageManagerInterface;
use App\Entity\PointType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class TwigGlobalService
{
    /** @var EntityManager */
    private $em;

    /** @var CityManager */
    private $cityManager;

    /** @var LanguageManager */
    private $languageManager;

    /**
     * @param EntityManagerInterface $em
     * @param CityManagerInterface $cityManager
     * @param LanguageManagerInterface $languageManager
     */
    public function __construct(
        EntityManagerInterface $em,
        CityManagerInterface $cityManager,
        LanguageManagerInterface $languageManager
    ) {
        $this->em = $em;
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

    /**
     * @return array
     */
    public function getPointTypes(): array
    {
        return $this->em->getRepository(PointType::class)->findAll();
    }
}