<?php

declare(strict_types=1);

namespace App\Repository;

use App\Components\Admin\Service\Pager\Paginator;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use DateTime;

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

    /**
     * @param int $page
     * @param int $max
     * @param string|null $tag
     *
     * @return Paginator
     *
     * @throws Exception
     */
    public function getActivePaginatedNews(int $page = 1, int $max = 100, ?string $tag = null): Paginator
    {
        $qb = $this->createQueryBuilder('n')
            ->andWhere('n.is_active = 1')
            ->andWhere('n.publish_date <= :publish_date')
            ->orderBy('n.publish_date', 'DESC')
            ->setParameter('publish_date', new DateTime());

        if ($tag !== null) {
            $qb->andWhere($qb->expr()->like('n.tags', ':tag'))
                 ->setParameter('tag', '%' . $tag . '%');
        }

        return new Paginator($qb->getQuery(), $page, $max);
    }
}
