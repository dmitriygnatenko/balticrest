<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Pager;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    private int $page;

    private int $max;

    /**
     * @param Query|QueryBuilder $query
     * @param int $page
     * @param int $max
     */
    public function __construct($query, int $page, int $max)
    {
        $this->page = $page;
        $this->max = $max;

        $firstResult = ($page - 1) * $max;

        $query->setFirstResult($firstResult)->setMaxResults($max);

        parent::__construct($query, true);
    }

    /**
     * @return int|null
     */
    public function getPrevPage(): ?int
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    /**
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        if ($this->count() > $this->max * $this->page) {
            return $this->page + 1;
        }

        return null;
    }
}