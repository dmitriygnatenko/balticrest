<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class NewsListDTO
{
    private ?int $prev_page = null;

    private ?int $next_page = null;

    /** @var NewsDTO[] */
    private array $news = [];

    /**
     * @return int|null
     */
    public function getPrevPage(): ?int
    {
        return $this->prev_page;
    }

    /**
     * @param int|null $prevPage
     *
     * @return NewsListDTO
     */
    public function setPrevPage(?int $prevPage): NewsListDTO
    {
        $this->prev_page = $prevPage;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        return $this->next_page;
    }

    /**
     * @param int|null $nextPage
     *
     * @return NewsListDTO
     */
    public function setNextPage(?int $nextPage): NewsListDTO
    {
        $this->next_page = $nextPage;

        return $this;
    }

    /**
     * @return array
     */
    public function getNews(): array
    {
        return $this->news;
    }

    /**
     * @param NewsDTO $newsDTO
     *
     * @return NewsListDTO
     */
    public function addNews(NewsDTO $newsDTO): NewsListDTO
    {
        $this->news[] = $newsDTO;

        return $this;
    }
}