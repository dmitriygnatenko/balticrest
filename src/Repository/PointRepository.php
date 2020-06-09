<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Point;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Components\Admin\Service\Pager\Paginator;
use Exception;

/**
 * @method Point|null find($id, $lockMode = null, $lockVersion = null)
 * @method Point|null findOneBy(array $criteria, array $orderBy = null)
 * @method Point[]    findAll()
 * @method Point[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Point::class);
    }

    /**
     * @param int $page
     * @param int $max
     *
     * @return Paginator
     *
     * @throws Exception
     */
    public function getAllForList(int $page = 1, int $max = 100): Paginator
    {
        $qb = $this->createQueryBuilder('p')->orderBy('p.id');

        return new Paginator($qb->getQuery(), $page, $max);
    }
}
