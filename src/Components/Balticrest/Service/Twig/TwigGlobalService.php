<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Provider\CityDataProvider;
use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
use App\Components\Balticrest\Service\Provider\LanguageDataProvider;
use App\Components\Balticrest\Service\Provider\LanguageDataProviderInterface;
use App\Components\Balticrest\Service\Provider\PointTypeProvider;
use App\Components\Balticrest\Service\Provider\PointTypeProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;

class TwigGlobalService
{
    /** @var string */
    private const TAG_TYPE_TITLE = 'title';

    /** @var string */
    private const TAG_TYPE_DESC = 'desc';

    /** @var string */
    private const TAG_TYPE_KEYWORDS = 'keywords';

    /** @var string */
    private const PAGE_TYPE_MAP = 'map';

    /** @var PointTypeProvider */
    private $pointTypeProvider;

    /** @var CityDataProvider */
    private $cityDataProvider;

    /** @var LanguageDataProvider */
    private $languageDataProvider;

    /** @var TranslatorInterface */
    private $translator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param PointTypeProviderInterface $pointTypeProvider
     * @param CityDataProviderInterface $cityDataProvider
     * @param LanguageDataProviderInterface $languageDataProvider
     */
    public function __construct(
        TranslatorInterface $translator,
        LoggerInterface $logger,
        PointTypeProviderInterface $pointTypeProvider,
        CityDataProviderInterface $cityDataProvider,
        LanguageDataProviderInterface $languageDataProvider
    ) {
        $this->pointTypeProvider = $pointTypeProvider;
        $this->cityDataProvider = $cityDataProvider;
        $this->languageDataProvider = $languageDataProvider;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cityDataProvider->getCachedActiveCitiesList();
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languageDataProvider->getCachedActiveLanguagesList();
    }

    /**
     * @return array
     */
    public function getPointTypes(): array
    {
        return $this->pointTypeProvider->getCachedPointTypes();
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getActivePointTypes(string $city): array
    {
        return $this->pointTypeProvider->getCachedActivePointTypesList($city);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getMapPageTitle(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_TITLE, self::PAGE_TYPE_MAP, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getMapPageDescription(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_DESC, self::PAGE_TYPE_MAP, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getMapPageKeywords(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_KEYWORDS, self::PAGE_TYPE_MAP, $city, $category);
    }

    /**
     * @param string $tagType
     * @param string $pageType
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    private function getSeoTrans(string $tagType, string $pageType, string $city, string $category): string
    {
        return $this->translator->trans(
            $pageType . '.' . ($category === '' ? 'default' : $category) . '.' . $tagType,
            [
                '%city_gen%' => $this->translator->trans('cities_genitive.' . $city),
                '%city%' => $this->translator->trans('cities.' . $city),
            ],
            'seo'
        );
    }
}