<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Point;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Components\Admin\Service\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @param int $max
     *
     * @return Paginator
     */
    public function getFilteredPoints(Request $request, int $max = 100): Paginator
    {
        $page = $request->query->getInt('page', 1);
        $city = $request->query->getInt('city');
        $category = $request->query->getInt('category');

        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id');

        if ($city) {
            $qb->andWhere('p.city = :city')->setParameter('city', $city);
        }

        if ($category) {
            $qb->andWhere('p.type = :type')->setParameter('type', $category);
        }

        return new Paginator($qb->getQuery(), $page, $max);
    }
}
