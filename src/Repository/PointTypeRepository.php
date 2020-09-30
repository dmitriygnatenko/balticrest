<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PointType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PointType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointType[]    findAll()
 * @method PointType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointTypeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointType::class);
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getCityPointTypes(string $city): array
    {
        $qb = $this->createQueryBuilder('t');

        $qb->distinct()
            ->join('t.points', 'p')
            ->join('p.city', 'c')
            ->andWhere('p.is_active = 1')
            ->andWhere('c.code = :city')
            ->setParameter('city', $city);

        return $qb->getQuery()->getResult();
    }
}
