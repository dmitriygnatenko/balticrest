<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\ArticleDTO;
use App\Components\Balticrest\Service\Helper\DataLanguageFilterInterface;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /** @var DataLanguageFilterInterface */
    private $dataLanguageFilter;

    /**
     * @param RequestStack $requestStack
     * @param DataLanguageFilterInterface $dataLanguageFilter
     */
    public function __construct(RequestStack $requestStack, DataLanguageFilterInterface $dataLanguageFilter)
    {
        $this->requestStack = $requestStack;
        $this->dataLanguageFilter = $dataLanguageFilter;
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

        $articleLangData = $this->dataLanguageFilter->filter($article->getArticleLangData(), $locale);

        if ($articleLangData !== null) {
            $dto->setTitle($articleLangData->getTitle())
                ->setText($articleLangData->getText())
                ->setMetaKeywords($articleLangData->getMetaKeywords())
                ->setMetaDescription($articleLangData->getMetaDescription());
        }

        return $dto;
    }
}