<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Sitemap;

use App\Components\Balticrest\Service\Provider\LanguageDataProviderInterface;
use App\Entity\Language;
use App\Repository\ArticleRepository;
use App\Repository\PointRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapGenerator implements SitemapGeneratorInterface
{
    /** @var string[] */
    private const NEWS_TAGS = ['events', 'transport', 'offers'];

    /** @var string */
    private const XML_TAG = '<?xml version="1.0" encoding="UTF-8"?>';

    /** @var string */
    private const URLSET_OPEN_TAG = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    /** @var string */
    private const URLSET_CLOSE_TAG = '</urlset>';

    /** @var string */
    private const TAB = "\t";

    /** @var string */
    private const FREQ_DAILY = 'daily';

    /** @var string */
    private const FREQ_WEEKLY = 'weekly';

    /** @var string */
    private const FREQ_MONTHLY = 'monthly';

    /** @var string */
    private string $baseUrl;

    private UrlGeneratorInterface $urlGenerator;

    private LanguageDataProviderInterface $languageDataProvider;

    private PointRepository $pointRepository;

    private ArticleRepository $articleRepository;

    /**
     * @param ContainerBagInterface $containerBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param LanguageDataProviderInterface $languageDataProvider
     * @param ArticleRepository $articleRepository
     * @param PointRepository $pointRepository
     */
    public function __construct(
        ContainerBagInterface $containerBag,
        UrlGeneratorInterface $urlGenerator,
        LanguageDataProviderInterface $languageDataProvider,
        ArticleRepository $articleRepository,
        PointRepository $pointRepository
    ) {
        $this->baseUrl = (string) $containerBag->get('app.base_url');
        $this->urlGenerator = $urlGenerator;
        $this->languageDataProvider = $languageDataProvider;
        $this->pointRepository = $pointRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return string
     */
    public function generateContent(): string
    {
        return
            self::XML_TAG . PHP_EOL .
            self::URLSET_OPEN_TAG . PHP_EOL .
            $this->getRootContent() .
            $this->getWebcamsContent() .
            $this->getArticlesContent() .
            $this->getNewsContent() .
            $this->getPointsContent() .
            self::URLSET_CLOSE_TAG;
    }

    /**
     * @return string
     */
    private function getRootContent(): string
    {
        $content = '';

        /** @var Language $language */
        foreach ($this->languageDataProvider->getLanguagesList() as $language) {
            $content .= $this->formatUrl(
                $this->urlGenerator->generate('main', ['_locale' => $language->getCode()]),
                 self::FREQ_WEEKLY
            );
        }

        return $content;
    }

    /**
     * @return string
     */
    private function getWebcamsContent(): string
    {
        $content = '';

        /** @var Language $language */
        foreach ($this->languageDataProvider->getLanguagesList() as $language) {
            $content .= $this->formatUrl(
                $this->urlGenerator->generate('webcams', ['_locale' => $language->getCode()]),
                self::FREQ_MONTHLY
            );
        }

        return $content;
    }

    /**
     * @return string
     */
    private function getArticlesContent(): string
    {
        $content = '';

        $articles = $this->articleRepository->findBy(['is_active' => true]);

        foreach ($articles as $article) {
            foreach ($article->getArticleLangData() as $langData) {
                $content .= $this->formatUrl(
                    $this->urlGenerator->generate(
                        $article->getUrl(),
                        ['_locale' => $langData->getLanguage()->getCode()]
                    ),
                    self::FREQ_MONTHLY
                );
            }
        }

        return $content;
    }

    /**
     * @return string
     */
    private function getNewsContent(): string
    {
        $content = '';

        /** @var Language $language */
        foreach ($this->languageDataProvider->getLanguagesList() as $language) {
            $content .= $this->formatUrl(
                $this->urlGenerator->generate('news', ['_locale' => $language->getCode()]),
                self::FREQ_DAILY
            );

            foreach (self::NEWS_TAGS as $tag) {
                $content .= $this->formatUrl(
                    $this->urlGenerator->generate('news_tag', ['_locale' => $language->getCode(), 'tag' => $tag]),
                    self::FREQ_DAILY
                );
            }
        }

        return $content;
    }

    /**
     * @return string
     */
    private function getPointsContent(): string
    {
        $content = '';

        $issetMaps = [];
        $issetPointLists = [];

        $points = $this->pointRepository->findBy(['is_active' => true]);
        foreach ($points as $point) {
            foreach ($point->getPointLangData() as $langData) {
                $url = $point->getUrl();
                $locale = $langData->getLanguage()->getCode();
                $city = $point->getCity()->getCode();
                $category = $point->getType()->getCode();

                $index = $city . $category;

                if (!isset($issetMaps[$index])) {
                    $issetMaps[$index] = true;

                    $content .= $this->formatUrl(
                        $this->urlGenerator->generate(
                            'map',
                            [
                                '_locale' => $locale,
                                'city' => $city,
                                'category' => $category,
                            ]
                        ),
                        self::FREQ_WEEKLY
                    );
                }

                if ($url !== null) {
                    $index = $locale . $city . $category;

                    if (!isset($issetPointLists[$index])) {
                        $issetPointLists[$index] = true;

                        $content .= $this->formatUrl(
                            $this->urlGenerator->generate(
                                'point_list',
                                [
                                    '_locale' => $locale,
                                    'city' => $city,
                                    'category' => $category,
                                ]
                            ),
                            self::FREQ_WEEKLY
                        );
                    }

                    $content .= $this->formatUrl(
                        $this->urlGenerator->generate(
                            'point',
                            [
                                '_locale' => $locale,
                                'city' => $city,
                                'category' => $category,
                                'url' => $url
                            ]
                        ),
                        self::FREQ_WEEKLY
                    );
                }
            }
        }

        return $content;
    }

    /**
     * @param string $loc
     * @param string $changeFreq
     *
     * @return string
     */
    private function formatUrl(string $loc, string $changeFreq): string
    {
        return
            self::TAB . '<url>' . PHP_EOL .
            self::TAB . self::TAB . '<loc>' . $this->baseUrl . $loc . '</loc>' . PHP_EOL .
            self::TAB . self::TAB . '<changefreq>' . $changeFreq . '</changefreq>'  . PHP_EOL .
            self::TAB . '</url>' . PHP_EOL;
    }
}