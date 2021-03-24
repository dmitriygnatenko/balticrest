<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Twig;

use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
use App\Components\Balticrest\Service\Provider\LanguageDataProviderInterface;
use App\Components\Balticrest\Service\Provider\PointTypeProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TwigGlobalService
{
    /** @var string */
    private const DEFAULT_CITY = 'svetlogorsk';

    /** @var string */
    private const DEFAULT_CATEGORY = 'hotels';

    /** @var string */
    private const TAG_TYPE_TITLE = 'title';

    /** @var string */
    private const TAG_TYPE_DESC = 'desc';

    /** @var string */
    private const TAG_TYPE_KEYWORDS = 'keywords';

    /** @var string */
    private const PAGE_TYPE_MAP = 'map';

    /** @var string */
    private const PAGE_TYPE_LIST = 'list';

    /** @var string */
    private const POINT_TYPE_LIST = 'point';

    private PointTypeProviderInterface $pointTypeProvider;

    private CityDataProviderInterface $cityDataProvider;

    private LanguageDataProviderInterface $languageDataProvider;

    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     * @param PointTypeProviderInterface $pointTypeProvider
     * @param CityDataProviderInterface $cityDataProvider
     * @param LanguageDataProviderInterface $languageDataProvider
     */
    public function __construct(
        TranslatorInterface $translator,
        PointTypeProviderInterface $pointTypeProvider,
        CityDataProviderInterface $cityDataProvider,
        LanguageDataProviderInterface $languageDataProvider
    ) {
        $this->pointTypeProvider = $pointTypeProvider;
        $this->cityDataProvider = $cityDataProvider;
        $this->languageDataProvider = $languageDataProvider;
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getDefaultCity(): string
    {
        return self::DEFAULT_CITY;
    }

    /**
     * @return string
     */
    public function getDefaultCategory(): string
    {
        return self::DEFAULT_CATEGORY;
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cityDataProvider->getCachedCitiesList();
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languageDataProvider->getCachedLanguagesList();
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
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getListPageTitle(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_TITLE, self::PAGE_TYPE_LIST, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getListPageDescription(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_DESC, self::PAGE_TYPE_LIST, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getListPageKeywords(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_KEYWORDS, self::PAGE_TYPE_LIST, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getPointPageDescription(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_DESC, self::POINT_TYPE_LIST, $city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getPointPageKeywords(string $city, string $category): string
    {
        return $this->getSeoTrans(self::TAG_TYPE_KEYWORDS, self::POINT_TYPE_LIST, $city, $category);
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