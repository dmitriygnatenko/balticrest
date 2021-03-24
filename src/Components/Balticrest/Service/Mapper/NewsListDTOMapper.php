<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Admin\Service\Pager\Paginator;
use App\Components\Balticrest\Service\DTO\NewsDTO;
use App\Components\Balticrest\Service\DTO\NewsListDTO;
use App\Components\Balticrest\Service\Helper\DataLanguageFilterInterface;
use App\Entity\News;
use Symfony\Component\HttpFoundation\RequestStack;
use Exception;

class NewsListDTOMapper
{
    private RequestStack $requestStack;

    private DataLanguageFilterInterface $dataLanguageFilter;

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
     * @param Paginator $paginator
     *
     * @return NewsListDTO
     *
     * @throws Exception
     */
    public function fill(Paginator $paginator): NewsListDTO
    {
        $listDTO = new NewsListDTO();

        $listDTO->setPrevPage($paginator->getPrevPage())
            ->setNextPage($paginator->getNextPage());

        $locale = $this->requestStack->getMasterRequest()->getLocale();

        foreach ($paginator->getIterator() as $news)
        {
            /** @var News $news */
            $newsLangData = $this->dataLanguageFilter->filter($news->getNewsLangData(), $locale);

            if ($newsLangData !== null) {
                $dto = (new NewsDTO())
                    ->setId($news->getId())
                    ->setImage($news->getImage())
                    ->setTags($news->getTags())
                    ->setPublishDate($news->getPublishDate())
                    ->setTitle($newsLangData->getTitle())
                    ->setText($newsLangData->getText());

                $listDTO->addNews($dto);
            }
        }

        return $listDTO;
    }
}