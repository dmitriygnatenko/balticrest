<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class SearchRecordDTO
{
    private ?string $title = null;

    private ?string $url = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return SearchRecordDTO
     */
    public function setTitle(?string $title): SearchRecordDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return SearchRecordDTO
     */
    public function setUrl(?string $url): SearchRecordDTO
    {
        $this->url = $url;

        return $this;
    }
}
