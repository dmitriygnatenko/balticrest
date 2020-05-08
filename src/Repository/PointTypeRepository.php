<?php

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
}
