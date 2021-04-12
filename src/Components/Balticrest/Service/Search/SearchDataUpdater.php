<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Search;

use App\Entity\Article;
use App\Entity\ArticleLangData;
use App\Entity\Interfaces\PointLangDataFieldsInterface;
use App\Entity\Point;
use App\Entity\PointLangData;
use App\Entity\SearchData;
use App\Repository\ArticleRepository;
use App\Repository\PointRepository;
use App\Repository\SearchDataRepository;

class SearchDataUpdater implements SearchDataUpdaterInterface, PointLangDataFieldsInterface
{
    /** @var int */
    private const TTL_IN_DAYS = 7;

    private PointRepository $pointRepository;

    private SearchDataRepository $searchDataRepository;

    private ArticleRepository $articleRepository;

    /**
     * @param SearchDataRepository $searchDataRepository
     * @param PointRepository $pointRepository
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        SearchDataRepository $searchDataRepository,
        PointRepository $pointRepository,
        ArticleRepository $articleRepository
    ) {
        $this->pointRepository = $pointRepository;
        $this->searchDataRepository = $searchDataRepository;
        $this->articleRepository = $articleRepository;
    }

    public function update(): void
    {
        $this->clean();
        $this->updatePoints();
        $this->updateActicles();
    }

    private function updatePoints(): void
    {
        $points = $this->pointRepository->getPointsForSearchData();

        /** @var Point $point */
        foreach ($points as $point) {
            $pointLangDataArray = $point->getPointLangData()->toArray();

            /** @var PointLangData $pointLangData */
            foreach ($pointLangDataArray as $pointLangData) {
                $id = $pointLangData->getId();

                /** @var SearchData $searchData */
                $searchData = $this->searchDataRepository->findOneByIdAndType($id, SearchData::TYPE_POINT_ID);

                if ($searchData === null) {
                    $searchText = $this->generateSearchTextFromPointLangData($pointLangData);
                    if ($searchText !== '') {
                        $this->searchDataRepository->insert($id, SearchData::TYPE_POINT_ID, $searchText);
                    }
                } else if ($point->getLastUpdateTime() > $searchData->getLastUpdateTime()) {
                    $searchText = $this->generateSearchTextFromPointLangData($pointLangData);
                    if ($searchText !== '') {
                        $searchData->setData($searchText);
                        $this->searchDataRepository->save($searchData);
                    }
                }
            }
        }
    }

    private function updateActicles(): void
    {
        $articles = $this->articleRepository->getArticlesForSearchData();

        /** @var Article $article */
        foreach ($articles as $article) {
            $articleLangDataArray = $article->getArticleLangData()->toArray();

            /** @var ArticleLangData $articleLangData */
            foreach ($articleLangDataArray as $articleLangData) {
                $id = $articleLangData->getId();

                /** @var SearchData $searchData */
                $searchData = $this->searchDataRepository->findOneByIdAndType($id, SearchData::TYPE_ARTICLE_ID);

                if ($searchData === null) {
                    $searchText = $this->generateSearchTextFromArticleLangData($articleLangData);
                    if ($searchText !== '') {
                        $this->searchDataRepository->insert($id, SearchData::TYPE_ARTICLE_ID, $searchText);
                    }
                } else if ($article->getLastUpdateTime() > $searchData->getLastUpdateTime()) {
                    $searchText = $this->generateSearchTextFromArticleLangData($articleLangData);
                    if ($searchText !== '') {
                        $searchData->setData($searchText);
                        $this->searchDataRepository->save($searchData);
                    }
                }
            }
        }
    }

    private function clean(): void
    {
        $this->searchDataRepository->clean(self::TTL_IN_DAYS);
    }

    /**
     * @param PointLangData $pointLangData
     *
     * @return string
     */
    private function generateSearchTextFromPointLangData(PointLangData $pointLangData): string
    {
        $text = $pointLangData->getTitle() . ' ';

        $text .= str_replace(
            [self::FIELD_DESC, self::FIELD_SHORT_DESC, self::FIELD_ADDRESS],
            ' ',
            implode(' ', $pointLangData->getData())
        );

        return trim(strip_tags($text));
    }

    /**
     * @param ArticleLangData $articleLangData
     *
     * @return string
     */
    private function generateSearchTextFromArticleLangData(ArticleLangData $articleLangData): string
    {
        $text = $articleLangData->getTitle() . ' '
            . $articleLangData->getMetaDescription() . ' '
            . $articleLangData->getMetaKeywords() . ' '
            . $articleLangData->getText();

        return trim(strip_tags($text));
    }
}