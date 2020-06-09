<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Pager;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    /** @var int  */
    private $page;

    /** @var int  */
    private $max;

    /**
     * @param Query|QueryBuilder $query
     * @param int $page
     * @param int $max
     */
    public function __construct($query, int $page, int $max)
    {
        $this->page = $page;
        $this->max = $max;

        $firstResult = $page == 1 ? 0 : $page * $max;

        $query->setFirstResult($firstResult)->setMaxResults($max);

        parent::__construct($query, true);
    }

    /**
     * @return int|bool
     */
    public function getPrevPage(): ?int
    {
        if ($this->count() > 1) {
            return $this->page - 1;
        } else {
            return null;
        }
    }

    /**
     * @return int|bool
     */
    public function getNextPage(): ?int
    {
        if ($this->count() > $this->max * $this->page) {
            return $this->page + 1;
        }

        return null;
    }
}