<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Sitemap;

use App\Components\Balticrest\Service\Provider\ArticleDataProviderInterface;
use App\Components\Balticrest\Service\Provider\LanguageDataProviderInterface;
use App\Entity\Article;
use App\Entity\Language;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapGenerator implements SitemapGeneratorInterface
{
    /** @var string[] */
    private const NEWS_TAGS = ['events', 'transport', 'offers'];

    /** @var string[] */
    private const ARTICLES = ['contacts', 'privacy_policy'];

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

    private ArticleDataProviderInterface $articleDataProvider;

    /**
     * @param ContainerBagInterface $containerBag
     * @param UrlGeneratorInterface $urlGenerator
     * @param LanguageDataProviderInterface $languageDataProvider
     * @param ArticleDataProviderInterface $articleDataProvider
     */
    public function __construct(
        ContainerBagInterface $containerBag,
        UrlGeneratorInterface $urlGenerator,
        LanguageDataProviderInterface $languageDataProvider,
        ArticleDataProviderInterface $articleDataProvider
    ) {
        $this->baseUrl = (string) $containerBag->get('app.base_url');
        $this->urlGenerator = $urlGenerator;
        $this->languageDataProvider = $languageDataProvider;
        $this->articleDataProvider = $articleDataProvider;
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
             $this->getArticlesContent() .
            $this->getNewsContent() .
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
                 self::FREQ_DAILY
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

        foreach (self::ARTICLES as $articleUrl) {
            /** @var Article $article */
            $article = $this->articleDataProvider->getArticle($articleUrl);
            if ($article !== null) {
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