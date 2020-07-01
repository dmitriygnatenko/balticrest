<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Manager\CityManager;
use App\Components\Balticrest\Service\Manager\CityManagerInterface;
use App\Components\Balticrest\Service\Manager\LanguageManager;
use App\Components\Balticrest\Service\Manager\LanguageManagerInterface;
use App\Components\Balticrest\Service\Manager\PointManager;
use App\Components\Balticrest\Service\Manager\PointManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;

class TwigGlobalService
{
    /** @var string */
    private const PART_TITLE = 'title';

    /** @var string */
    private const PART_DESC = 'desc';

    /** @var string */
    private const PART_KEYWORDS = 'keywords';

    /** @var array */
    private $allowed_types = ['map'];

    /** @var PointManager */
    private $pointManager;

    /** @var CityManager */
    private $cityManager;

    /** @var LanguageManager */
    private $languageManager;

    /** @var LanguageManager */
    private $translator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param PointManagerInterface $pointManager
     * @param CityManagerInterface $cityManager
     * @param LanguageManagerInterface $languageManager
     */
    public function __construct(
        TranslatorInterface $translator,
        LoggerInterface $logger,
        PointManagerInterface $pointManager,
        CityManagerInterface $cityManager,
        LanguageManagerInterface $languageManager
    ) {
        $this->pointManager = $pointManager;
        $this->cityManager = $cityManager;
        $this->languageManager = $languageManager;
        $this->translator = $translator;
        $this->logger = $logger;
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

    /**
     * @param string $type
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getTitle(string $type, string $city, string $category): string
    {
        return $this->getSeoTrans(self::PART_TITLE, $type, $city, $category);
    }

    /**
     * @param string $type
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getDescription(string $type, string $city, string $category): string
    {
        return $this->getSeoTrans(self::PART_DESC, $type, $city, $category);
    }

    /**
     * @param string $type
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getKeywords(string $type, string $city, string $category): string
    {
        return $this->getSeoTrans(self::PART_KEYWORDS, $type, $city, $category);
    }

    /**
     * @param string $part
     * @param string $type
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    private function getSeoTrans(string $part, string $type, string $city, string $category): string
    {
        if (!in_array($type, $this->allowed_types, true)) {
            $this->logger->warning('Translation type ' . $type . ' not found');

            return '';
        }

        return $this->translator->trans(
            'seo.' . $type . '.' . ($category === '' ? 'default' : $category) . '.' . $part,
            [
                '%city_gen%' => $this->translator->trans('cities_genitive.' . $city),
                '%city%' => $this->translator->trans('cities.' . $city),
            ]
        );
    }
}