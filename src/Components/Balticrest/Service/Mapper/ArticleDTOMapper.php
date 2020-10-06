<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\ArticleDTO;
use App\Entity\Article;
use App\Entity\ArticleLangData;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param Article $article
     *
     * @return ArticleDTO
     */
    public function fill(Article $article): ArticleDTO
    {
        $dto = new ArticleDTO();

        $locale = $this->requestStack->getMasterRequest()->getLocale();

        /** @var ArticleLangData $articleLangData */
        $articleLangData = null;

        $articleLangDataCollection = $article->getArticleLangData();

        if ($locale === 'ru') {
            // Данные на русском всегда присутствуют, не проверяем другие языки
            $result = $articleLangDataCollection->filter(static function($item) {
                /** @var ArticleLangData $item */
                return $item->getLanguage()->getCode() === 'ru';
            });

            if (!$result->isEmpty()) {
                $articleLangData = $result->first();
            }
        } else if ($locale === 'en') {
            // Данные на английском всегда присутствуют, не проверяем другие языки
            $result = $articleLangDataCollection->filter(static function($item) {
                /** @var ArticleLangData $item */
                return $item->getLanguage()->getCode() === 'en';
            });

            if (!$result->isEmpty()) {
                $articleLangData = $result->first();
            }
        } else {
            // Вначале проверяем данные на запрошенном языке
            // Если они отсутствуют то берем английскую версию
            $result = $articleLangDataCollection->filter(static function($item) use ($locale) {
                /** @var ArticleLangData $item */
                return $item->getLanguage()->getCode() === $locale;
            });

            if (!$result->isEmpty()) {
                $articleLangData = $result->first();

                if (trim($articleLangData->getTitle()) === '') {
                    $articleLangData = null;
                }
            }

            if ($articleLangData === null) {
                $result = $articleLangDataCollection->filter(static function($item) {
                    /** @var ArticleLangData $item */
                    return $item->getLanguage()->getCode() === 'en';
                });

                if (!$result->isEmpty()) {
                    $articleLangData = $result->first();
                }
            }
        }

        if ($articleLangData !== null) {
            $dto->setTitle($articleLangData->getTitle())
                ->setText($articleLangData->getText())
                ->setMetaKeywords($articleLangData->getMetaKeywords())
                ->setMetaDescription($articleLangData->getMetaDescription());
        }

        return $dto;
    }
}