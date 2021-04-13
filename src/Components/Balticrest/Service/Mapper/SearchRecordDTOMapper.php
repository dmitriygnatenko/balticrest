<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\SearchRecordDTO;
use App\Entity\Article;
use App\Entity\Point;
use App\Entity\SearchData;
use App\Repository\ArticleLangDataRepository;
use App\Repository\PointLangDataRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchRecordDTOMapper
{
    private PointLangDataRepository $pointLangDataRepository;

    private ArticleLangDataRepository $articleLangDataRepository;

    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param PointLangDataRepository $pointLangDataRepository
     * @param ArticleLangDataRepository $articleLangDataRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        PointLangDataRepository $pointLangDataRepository,
        ArticleLangDataRepository $articleLangDataRepository,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->pointLangDataRepository = $pointLangDataRepository;
        $this->articleLangDataRepository = $articleLangDataRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param SearchData[] $searchDataRecords
     *
     * @return SearchRecordDTO[]
     */
    public function fillAll(array $searchDataRecords): array
    {
        $searchRecordDTOs = [];

        foreach ($searchDataRecords as $record) {
            $dto = $this->fill($record);
            if ($dto !== null) {
                $searchRecordDTOs[] = $dto;
            }
        }

        return $searchRecordDTOs;
    }

    /**
     * @param SearchData $searchData
     *
     * @return SearchRecordDTO|null
     */
    public function fill(SearchData $searchData): ?SearchRecordDTO
    {
        $dto = null;

        $objectId = (int) $searchData->getObjectId();

        switch ($searchData->getObjectTypeId())
        {
            case SearchData::TYPE_POINT_ID:
                $dto = $this->fillFromPoint($objectId);
                break;
            case SearchData::TYPE_ARTICLE_ID:
                $dto = $this->fillFromArticle($objectId);
                break;
        }

        return $dto;
    }

    /**
     * @param int $id
     *
     * @return SearchRecordDTO|null
     */
    private function fillFromPoint(int $id): ?SearchRecordDTO
    {
        $pointLangData = $this->pointLangDataRepository->find($id);
        if ($pointLangData === null) {
            return null;
        }

        $title = $pointLangData->getTitle();
        if (empty($title)) {
            return null;
        }

        /** @var Point $point */
        $point = $pointLangData->getPoint();
        $url = $point->getUrl();
        if (empty($url)) {
            return null;
        }

        $pointUrl = $this->urlGenerator->generate(
            'point',
            [
                '_locale' => $pointLangData->getLanguage()->getCode(),
                'city' => $point->getCity()->getCode(),
                'category' => $point->getType()->getCode(),
                'url' => $url,
            ]
        );

        return (new SearchRecordDTO())
            ->setTitle($title)
            ->setUrl($pointUrl);
    }

    /**
     * @param int $id
     *
     * @return SearchRecordDTO|null
     */
    private function fillFromArticle(int $id): ?SearchRecordDTO
    {
        $articleLangData = $this->articleLangDataRepository->find($id);
        if ($articleLangData === null) {
            return null;
        }

        $title = $articleLangData->getTitle();
        if (empty($title)) {
            return null;
        }

        /** @var Article $article */
        $article = $articleLangData->getArticle();
        $url = $article->getUrl();
        if (empty($url)) {
            return null;
        }

        $articleUrl = $this->urlGenerator->generate(
            $url,
            [
                '_locale' => $articleLangData->getLanguage()->getCode(),
            ]
        );

        return (new SearchRecordDTO())
            ->setTitle($title)
            ->setUrl($articleUrl);
    }
}