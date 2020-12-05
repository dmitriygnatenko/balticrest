<?php

declare(strict_types=1);

namespace App\Repository;

use App\Components\Admin\Service\Pager\Paginator;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param int $page
     * @param int $max
     *
     * @return Paginator
     */
    public function getPaginatedNews(int $page = 1, int $max = 100): Paginator
    {
        $qb = $this->createQueryBuilder('n')
            ->orderBy('n.publish_date', 'DESC');

        return new Paginator($qb->getQuery(), $page, $max);
    }
}
